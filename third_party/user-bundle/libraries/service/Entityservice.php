<?php
/**
 * Created by PhpStorm.
 * Date: 24.09.2016
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Entityservice
{
    protected $CI;
    protected $table;
    protected $dependenceEntities;
    protected $columns;
    protected $updatedCols;
    protected $queryCols;
    protected $queryDefined;
    protected $queryOrderBy;
    private $lastInsertId;

    public function __construct()
    {
        $this->CI =& get_instance();
        // table name
        $this->table = '';
        // table columns
        $this->columns = [];
        // stores updated columns names
        $this->updatedCols = [];
        // default query columns
        $this->queryCols = "";
        // defined queries
        $this->queryDefined = [];
        // default query order by
        $this->queryOrderBy = [];
        // last insert id
        $this->lastInsertId = 0;
        // dependence entities
        $this->dependenceEntities = [];
    }
    public function query ($queryId, $format='')
    {
        if (isset($this->queryDefined[$queryId]))
        {
            $query = $this->queryDefined[$queryId];

            if ( ! empty($format)) {
                if (is_string($format)) {
                    $query = sprintf($query, $format);
                } else if (is_array($format)) {
                    $query = vsprintf($query, $format);
                }
            }
            $res = $this->CI->db->query($query);
            return (is_object($res) && method_exists($res,'result_array')) ? $res->result_array(): null;
        }
        return [];
    }
    public function find ($select='', $where=[], $limit=0, $orderby=[], $distinct=true, $setEscpape = true)
    {
        // get defined columns include DATE_FORMAT, etc
        if ($select=='')
        {
            $select = $this->queryCols;

            if ( empty($orderby) && ! empty($this->queryOrderBy))
            {
                $this->dbOrderBy($this->queryOrderBy);
            }
        }
        $this->CI->db->select($select)
            ->from($this->table);

        if (! empty($where)) {
            if(! $setEscpape){
                foreach($where as $k=>$v){
                    $this->CI->db->where($k, $v, false);
                }
            } else {
                $this->CI->db->where($where);
            }

        }
        if ($limit !== 0) {
            if (is_array($limit) && count($limit) >1)
            {
                $this->CI->db->limit($limit[0],$limit[1]);
            } else {
                $this->CI->db->limit($limit);
            }

        }
        if ( ! empty($orderby)) {
            $this->dbOrderBy($orderby);
        }
        if ($distinct) {
            $this->CI->db->distinct();
        }

        $query = $this->CI->db->get();
        $result = ($limit === 1) ? $query->row() : $query->result_array();

        return $result;
    }
    public function filter ($data, $filterPrimaryKey = true)
    {
        $res = [];
        if (isset($this->columns)) {
            foreach($data as $key => $val){
                if (array_key_exists($key, $this->columns)) {
                    if ($filterPrimaryKey) {
                        if ( ! in_array('PRIMARY_KEY',$this->columns[$key])) {
                            $res[$key] = trim($val);
                        }
                    } else {
                        $res[$key] = trim($val);
                    }
                }
            }
        }
        return $res;
    }
    private function filterUnique ($key, $val)
    {
        $check = $this->find('id',[$key=>$val],1);
        return ($check===null) ? true:false;
    }
    public function filterUpdate ($data,$where)
    {
        $res = [];
        $entity = $this->find('*',$where,1);

        if ($entity !== null && isset($this->columns))
        {
            foreach($data as $key => $val){
                if (property_exists($entity, $key) && ! in_array('PRIMARY_KEY',$this->columns[$key]))
                {
                    if($entity->{$key} != $val)
                    {
                        if (in_array('UNIQUE',$this->columns[$key]))
                        {
                            if ($this->filterUnique($key, $val)) {
                                $res[$key] = $val;
                            } else {
                                $this->CI->messagemanager->setError('page_error_db_unique');
                                $res = [];
                                break;
                            }
                        } else {
                            $res[$key] = $val;
                            $this->updatedCols[$key] = [
                                'oldVal' => $entity->{$key},
                                'newVal' => $val
                            ];
                        }
                    }
                }
            }
        }
        return $res;
    }
    public function create ($data, $setInfo = true)
    {
        $data = $this->filter($data);
        $affectedRows = 0;

        if( ! empty($data)) {
            $this->CI->db->insert($this->table, $data);
            $this->lastInsertId = $this->CI->db->insert_id();

            $affectedRows = $this->CI->db->affected_rows();

            if ($affectedRows) {
                if ($setInfo) {
                    $this->CI->messagemanager->setInfo('page_info_data_saved');
                }
            } else {
                $this->CI->messagemanager->setError('page_error_db_insert');
            }
        } else {
            $this->CI->messagemanager->setError('page_error_data_empty');
        }
        return $affectedRows;
    }
    public function update ($data, $where, $setInfo = true, $setEscpape = true)
    {
        $data = $this->filterUpdate ($data,$where);
        $affectedRows = 0;

        if (! empty($data))
        {
            if ( ! $setEscpape) {
                foreach($data as $k=>$v){
                    $this->CI->db->set($k, $v, false);
                }
            } else {
                $this->CI->db->set($data);
            }
            $this->CI->db->where($where)->update($this->table);
            $affectedRows = $this->CI->db->affected_rows();

            if ($affectedRows) {
                if ($setInfo) {
                    $this->CI->messagemanager->setInfo('page_info_update_success');
                }
            } else {
                $this->CI->messagemanager->setError('page_error_update');
            }
        } else {
            $this->CI->messagemanager->setError('page_error_data_empty');
        }
        return $affectedRows;
    }
    public function delete ($where)
    {
        $where = $this->filter($where, false);

        if(empty($where)) {
            $this->CI->messagemanager->setError('page_error_delete');
            return false;
        }

        $entry = $this->find('id',$where,1);

        if ($entry == null) {
            return true;
        }

        $this->CI->db->delete($this->table, $where);
        $res = $this->find('id',$where,1);

        if ($res == null) {
            if ( ! empty($this->dependenceEntities)) {

                $parentIdKey = $this->indexKey();
                $where = [$parentIdKey => $entry->id];

                foreach($this->dependenceEntities as $entity){
                    $this->deleteDependence($entity, $where);
                }
            }
        } else {
            $this->CI->messagemanager->setError('page_error_delete');
        }
        return ($res == null) ? true:false;
    }
    public function findWithDependence ($dependenceEntity='', $select='*', $where=[], $limit=0, $orderby=[])
    {
        $this->CI->db->select($select);
        $this->CI->db->from($this->table);

        $entity = $this->dependenceEntity($dependenceEntity);

        if ($entity !== null)
        {
            $tableName = $entity->tableName();
            $this->CI->db->join($tableName, $this->table.'_id = '.$this->table.'.id');

        } else {
            foreach($this->dependenceEntities as $dependence){

                $entity = $this->dependenceEntity($dependence);
                $tableName = $entity->tableName();
                $this->CI->db->join($tableName, $this->table.'_id = '.$this->table.'.id');

            }
        }
        if ( ! empty($where))
        {
            $this->CI->db->where($where);
        }
        if ($limit != 0)
        {
            if (is_array($limit) && count($limit) > 1)
            {
                $this->CI->db->limit($limit[0],$limit[1]);
            }
            else if (! is_array($limit))
            {
                $this->CI->db->limit($limit);
            }

        }
        if ( ! empty($orderby))
        {
            $this->dbOrderBy($orderby);
        }
        $query = $this->CI->db->get();

        return ($query != null) ? $query->result_array():null;
    }
    public function queryDependence ($dependenceEntity, $queryId, $format='')
    {
        $entity = $this->dependenceEntity($dependenceEntity);

        if ($entity !== null)
        {
            return $entity->query($queryId, $format);
        }
        return [];
    }
    public function findDependence ($dependenceEntity, $select='*', $where=[], $limit=0, $orderby=[], $distinct=true, $setEscpape = true)
    {
        $entity = $this->dependenceEntity($dependenceEntity);

        if ($entity !== null)
        {
            return $entity->find($select, $where, $limit, $orderby, $distinct, $setEscpape);
        }
        return null;
    }
    public function createDependence ($dependenceEntity, $data, $setInfo = true)
    {
        $affectedRows = 0;
        $entity = $this->dependenceEntity($dependenceEntity);

        if ($entity !== null) {

            $data = $entity->filter($data);

            if( ! empty($data)) {

                $affectedRows = $entity->create($data, false);

                if ($affectedRows && $setInfo) {
                    $this->CI->messagemanager->setInfo('page_info_data_saved');
                }
            } else {
                $this->CI->messagemanager->setError('page_error_data_empty');
            }
        }

        return $affectedRows;
    }
    public function updateDependence ($dependenceEntity, $data, $where)
    {
        $affectedRows = 0;
        $entity = $this->dependenceEntity($dependenceEntity);

        if ($entity !== null)
        {
            $affectedRows = $entity->update($data, $where, false);
        }

        return $affectedRows;
    }
    public function deleteDependence ($dependenceEntity, $where)
    {
        $entity = $this->dependenceEntity($dependenceEntity);

        if ($entity !== null)
        {
            return $entity->delete($where);
        }
        return false;
    }
    public function updateDependenceZIndex ($dependenceEntity, $storedArgs = [])
    {
        $entity = $this->dependenceEntity($dependenceEntity);

        if ($entity !== null) {
            $entity->updateZIndex($storedArgs['id'],$storedArgs);
        }
    }

    public function updateZIndex ($entityId, $storedArgs = [])
    {
        if (array_key_exists('maxZIndex',$storedArgs)) {
            if ($storedArgs['zIndex'] < $storedArgs['maxZIndex']) {
                $cols = ['zIndex'=>'zIndex+1'];
                $where = ['id !='=>$entityId, 'zIndex >='=>$storedArgs['zIndex']];
                if (array_key_exists('parent_id_key',$storedArgs) && array_key_exists('parent_id_value',$storedArgs)) {
                    $where = ['id !='=>$entityId, $storedArgs['parent_id_key']=>$storedArgs['parent_id_value'],'zIndex >='=>$storedArgs['zIndex']];
                }
            }
        } else if (array_key_exists('oldVal',$storedArgs) && array_key_exists('newVal',$storedArgs)) {
            if ($storedArgs['oldVal']*1 > $storedArgs['newVal']*1) {
                $cols = ['zIndex'=>'zIndex+1'];
                $where = ['id !='=>$entityId, 'zIndex >='=>$storedArgs['newVal'], 'zIndex <'=>$storedArgs['oldVal']];
                if (array_key_exists('parent_id_key',$storedArgs) && array_key_exists('parent_id_value',$storedArgs)) {
                    $where = ['id !='=>$entityId, $storedArgs['parent_id_key']=>$storedArgs['parent_id_value'], 'zIndex >='=>$storedArgs['newVal'], 'zIndex <'=>$storedArgs['oldVal']];
                }
            } else {
                $cols = ['zIndex'=>'zIndex-1'];
                $where = ['id !='=>$entityId, 'zIndex <='=>$storedArgs['newVal'], 'zIndex >'=>$storedArgs['oldVal']];
                if (array_key_exists('parent_id_key',$storedArgs) && array_key_exists('parent_id_value',$storedArgs)) {
                    $where = ['id !='=>$entityId, $storedArgs['parent_id_key']=>$storedArgs['parent_id_value'], 'zIndex <='=>$storedArgs['newVal'], 'zIndex >'=>$storedArgs['oldVal']];
                }
            }
        } else if (array_key_exists('deletedZIndex',$storedArgs)) {
            $cols = ['zIndex'=>'zIndex-1'];
            $where = ['zIndex >'=>$storedArgs['deletedZIndex']];
            if (array_key_exists('parent_id_key',$storedArgs) && array_key_exists('parent_id_value',$storedArgs)) {
                $where = ['zIndex >'=>$storedArgs['deletedZIndex'], $storedArgs['parent_id_key']=>$storedArgs['parent_id_value']];
            }
        }

        if (isset($cols) && isset($where)) {
            $this->update($cols, $where, false, false);
        }

    }
    public function getDependenceLastInsertId ($dependenceEntity)
    {
        $entity = $this->dependenceEntity($dependenceEntity);

        if ($entity !== null)
        {
            return $entity->getLastInsertId();
        }
        return 0;
    }
    public function depenedenceColIsUpadted ($dependenceEntity, $colname)
    {
        $entity = $this->dependenceEntity($dependenceEntity);

        if ($entity !== null) {
            return (array_key_exists($colname,$entity->updatedCols)) ? $entity->updatedCols[$colname] : [];
        }
        return [];
    }
    public function getDependenceEntities ()
    {
        return $this->dependenceEntities;
    }
    public function dependenceIndexKey ($dependenceEntity)
    {
        $entity = $this->dependenceEntity($dependenceEntity);

        if ($entity !== null)
        {
            return $entity->table.'_id';
        }
        return '';
    }
    public function getLastInsertId ()
    {
        return $this->lastInsertId;
    }
    public function getOrderBy ()
    {
        return $this->queryOrderBy;
    }
    public function getEntriesCount ($where = [], $dependenceEntity = '')
    {
        $counts = 0;

        if ($dependenceEntity == '')
        {
            $counts = $this->find('count(id) as counts',$where,1);
        }else{

            $entity = $this->dependenceEntity($dependenceEntity);

            if ($entity !== null)
            {
                $counts = $entity->find('count(id) as counts',$where,1);
            }
        }

        return ($counts != null) ? $counts->counts: 0 ;
    }
    public function colIsUpadted ($colname)
    {
        return (array_key_exists($colname,$this->updatedCols)) ? $this->updatedCols[$colname] : [];
    }
    public function tableName ()
    {
        return $this->table;
    }
    public function fieldData ($dependenceEntity = '')
    {
        if($dependenceEntity != '')
        {
            $entity = $this->dependenceEntity($dependenceEntity);

            if ($entity !== null)
            {
                return $this->CI->db->field_data($entity->table);
            }
        }

        return $this->CI->db->field_data($this->table);
    }
    public function columnData ($col,$where,$dependenceEntity = '')
    {
        $res = null;

        if($dependenceEntity != '')
        {
            $entity = $this->dependenceEntity($dependenceEntity);

            if ($entity !== null)
            {
                $res = $entity->find($col,$where,1);
            }
        } else {
            $res = $this->find($col,$where,1);
        }

        if ($res != null) {
            $res = $res->{$col};
        }

        return $res;
    }
    public function indexKey ()
    {
        return $this->table.'_id';
    }
    private function dependenceEntity ($dependenceEntity)
    {
        $dependenceEntityPath = explode('/',$dependenceEntity);
        $dependence = '';

        if (count($dependenceEntityPath) > 1) {
            if (is_array($this->dependenceEntities) && in_array($dependenceEntityPath[0], $this->dependenceEntities))
            {
                $dependence = $dependenceEntityPath[1];
            }
        } else {
            if (is_array($this->dependenceEntities) && in_array($dependenceEntity, $this->dependenceEntities))
            {
                $dependence = $dependenceEntity;
            }
        }

        $entity = $this->CI->entitymanager->instance($dependence);

        if ($entity == null){
            $entity = $this->CI->{$dependence};
        }

        return $entity;
    }
    private function dbOrderBy ($orderby)
    {
        foreach($orderby as $key => $val){
            $this->CI->db->order_by($key, $val);
        }
    }
}