<?php
/**
 * Created by PhpStorm.
 * Date: 30.09.2016
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class Entitiesmodel extends MY_Model
{
    private $configEntities;
    private $queryData;

    public function __construct()
    {
        parent::__construct('entitiesentity');
        $this->configEntities = [];
        $this->queryData = [];
        $this->view['filter']['entities_config_id'] = $this->getIdArray($this->entity->find(''),'id,name');
        $this->view['filter']['app_dependence'] = ['public','protected'];

        $this->viewAvailabelTables();
    }
    public function all ()
    {
        if ( ! empty($this->modelFilter))
        {
            $this->view['results'] = $this->getFilteredResults();
        } else {

            $this->entryCounts = $this->entity->getEntriesCount([],'entities');
            $this->setViewPagination ($this->configPagination["limit"]["limitmax"], $this->configPagination["limit"]["limitstep"]);
            $limit = '';

            if ($this->entryCounts)
            {
                $limit  = ' LIMIT '.$this->configPagination["limit"]["limitmax"];
            }
            $this->view['results'] = $this->buildResult($this->entity->query('entities', ['', $limit]));
        }
        $this->view['entities_config_id'] = (isset($this->modelFilter['entities_config_id'])) ? $this->modelFilter['entities_config_id'] : '';
        $this->view['app_dependence'] = (isset($this->modelFilter['app_dependence'])) ? $this->modelFilter['app_dependence'] : '';

        if ($this->view['app_dependence'] != '') {
            $this->view['filter']['entities_config_id'] = $this->getIdArray($this->entity->find('',['app_dependence'=>$this->view['app_dependence']]),'id,name');
        }
    }
    public function add()
    {
        $this->view['controllerMsg'] = $this->lang->line('js_controller_msg');
    }
    public function createFileConf ()
    {
        $this->load->helper('file');
        $fileName = $this->post['syncname'].'.php';
        $pathArgs = ['third_party','user-bundle','config','entity',$fileName];
        $path = APPPATH.implode(DIRECTORY_SEPARATOR, $pathArgs);
        $isSet = false;
        $entries = $this->entity->findWithDependence('entities','entities_config.name as configName,entities.*',['entities_config.name'=>$this->post['syncname']],0);

        if( ! empty($entries)) {

            $content = '<?php defined("BASEPATH") OR exit("No direct script access allowed");';

            foreach($entries as $entry) {
                $entry['columns'] = $this->entity->findDependence('entities/entitiescolumns','key,value',['entities_id'=>$entry['id']]);
                $entry['queryDefined'] = $this->entity->findDependence('entities/entitiesquerydefined','key,value',['entities_id'=>$entry['id']]);
                $content .= $this->getConfTemplate($entry);
            }
            if ( $fp = fopen($path, 'w')) {
                fwrite($fp, $content);
                $isSet = true;
                $this->setRedirectResponse();
            } else {
                $this->messagemanager->setError('page_error_data_empty');
            }
            fclose($fp);
            if ($isSet) {
                chmod($path, 0774);
            }
        }
    }
    public function createDbConf ()
    {
        $conf = $this->entity->find('id, app_dependence',['name'=>$this->post['syncname']],1);
        $isOk = true;

        if ( $conf != null) {
            if($conf->app_dependence == 'public') {
                $this->entity->query('deleteConfig',$conf->id);
            }else {
                $isOk = false;
                $this->messagemanager->setError('error_conf_is_protected');
            }
        }
        if ($isOk) {

            $this->loadEntities($this->post['syncname'],'');

            if ($this->buildEntityEntries()) {
                $this->setRedirectResponse();
            } else {

                $conf = $this->entity->find('id',['name'=>$this->post['syncname']],1);

                if ( $conf != null) {
                    $this->entity->query('deleteConfig',$conf->id);
                    $this->messagemanager->setError('error_conf_is_bad');
                }
            }
        }

    }
    public function createConf()
    {
        $masterConf = json_decode($this->post['entityconf']);

        if (is_array($masterConf) && ! empty($masterConf))
        {
            $actionOk = $this->saveMasterConf($masterConf);

            if($actionOk){
                $this->messagemanager->setInfo('page_info_data_saved_redirect');
            } else {
                $this->messagemanager->setError('page_error_data_empty');
            }
        } else {
            $this->messagemanager->setError('page_error_data_empty');
        }
    }
    public function deleteConfig ()
    {
        $entry = $this->entity->find('app_dependence', $this->post, 1);

        if($entry != null && $entry->app_dependence == 'public') {
            $this->entity->query('deleteConfig', $this->post['id']);
            $this->setRedirectResponse();
        } else {
            $this->messagemanager->setError('error_conf_is_protected');
        }
    }
    public function deleteEntitiy()
    {
        $entry = $this->entity->findDependence('entities','*', $this->post, 1);
        $search = null;

        if($entry != null && $entry->app_dependence == 'public')
        {
            $entryCount = $this->entity->getEntriesCount(['entities_config_id'=>$entry->entities_config_id],'entities');

            if($entryCount < 2){

                $conf = $this->entity->find('app_dependence',['id'=>$entry->entities_config_id],1);

                if ( $conf != null){
                    if($conf->app_dependence == 'public') {
                        if ($this->entity->delete(['id'=>$entry->entities_config_id])) {
                            $this->setRedirectResponse();
                        }
                    } else {
                        $this->messagemanager->setError('error_conf_is_protected');
                    }
                }
            } else {
                if ($entry->dependence_entities != '') {
                    $search = $this->entity->findDependence('entities','id', ['FIND_IN_SET ('=>'name, "'.$entry->dependence_entities.'")'], 0,[],true,false);
                }
                if($search != null) {
                    $this->messagemanager->setError('error_entity_has_dependence');
                    $this->messagemanager->setError('error_entity_dependencies','',[$entry->dependence_entities]);
                }else {
                    if ($this->entity->deleteDependence('entities', $this->post)) {
                        $this->setRedirectResponse();
                    }
                }
            }
        }
    }
    public function updateConf()
    {
        $conf = json_decode($this->post['entityconf']);

        if (is_array($conf) && ! empty($conf))
        {
            $actionOk = $this->saveIntegrationConf($conf);

            if($actionOk){
                $this->messagemanager->setInfo('page_info_data_saved_redirect');
            } else {
                $this->messagemanager->setError('page_error_data_empty');
            }
        } else {
            $this->messagemanager->setError('page_error_data_empty');
        }
    }
    public function updateAppDependence ()
    {
        $this->entity->query('updateAppDependence', $this->post['syncname']);
        $this->setRedirectResponse();
    }
    public function getIntegrationConf ()
    {
        $this->responseData = ["data" => $this->getNewElemnts()];
    }
    public function getNewConf ()
    {
        $res = $this->buildMasterConf($this->post['tablename'],0);
        $this->responseData['masterconf'] = $res;
    }
    public function getTableConf ()
    {
        $this->responseData = [
            "data" => $this->entity->query('tableColumns',[$this->db->database, $this->post['tablename']]),
            'configIds'=>$this->entity->query('tableConfiguration',[$this->db->database, $this->post['tablename']]),
            'hasDependencies'=>$this->getTableHasDependencies(),
            'isDependence'=>$this->entity->query('tableIsDependence',[$this->db->database, $this->post['tablename']])
        ];
    }
    public function sync ()
    {
        $confFiles = $this->getConfigFiles();
        $exitingConf = [];

        foreach($confFiles as $fls) {
            $fls = str_replace('.php','',$fls);
            $exitingConf[] = $fls;
        }
        $exitingConf = implode(",",$exitingConf);
        $this->view['syncConf'] = $this->getSyncConf($exitingConf);
    }
    public function tableIsDependence ()
    {
        $this->responseData = [
            'isDependence'=>$this->entity->query('tableIsDependence',[$this->db->database, $this->post['tablename']])
        ];
    }
    private function saveIntegrationConf ($conf)
    {
        $actionOk = true;

        foreach($conf as $k=>$v){

            $data = $this->getConfEntry ($v->config_id, $v);

            if ($this->entity->createDependence('entities', $data, false))
            {
                $this->post['tablename'] = $v->tablename;
                $this->createEntityColumns($this->entity->getDependenceLastInsertId('entities'));
            } else {
                $actionOk = false;
                break;
            }
        }
        return $actionOk;
    }
    private function saveMasterConf ($masterConf)
    {
        $entitiesConfigId = 0;
        $actionOk = true;

        foreach($masterConf as $k=>$v){
            if ($v->dependence_level < 1 && $entitiesConfigId < 1) {
                if ($this->entity->create(['name'=>$v->configName], false))
                {
                    $entitiesConfigId = $this->entity->getLastInsertId();
                }
            }
            if($entitiesConfigId)
            {
                $data = $this->getConfEntry ($entitiesConfigId, $v);

                if ($this->entity->createDependence('entities', $data, false))
                {
                    $this->post['tablename'] = $v->tablename;
                    $this->createEntityColumns($this->entity->getDependenceLastInsertId('entities'));
                } else {
                    $actionOk = false;
                    break;
                }
            }
        }
        if(! $actionOk){
            if ($entitiesConfigId > 0){
                $this->entity->query('deleteConfig', $entitiesConfigId);
            }
        }
        return $actionOk;
    }
    private function createEntityColumns ($entityId)
    {
        $fieldData = $this->db->query('SHOW COLUMNS FROM '.$this->post['tablename']);
        $fieldData = $fieldData->result_array();

        foreach($fieldData as $k=>$v){

            $pri = (preg_match('/PRI/',$v['Key'])) ? 'PRIMARY_KEY' : '';
            $uni = (preg_match('/UNI/',$v['Key'])) ? 'UNIQUE' : '';
            $type = explode('(',$v['Type']);
            $type = strtoupper($type[0]);
            $result = [];
            $result['entities_id'] = $entityId;
            $result['key'] = $v['Field'];

            if ($v['Field'] == 'id')  {
                $result['value'] =  ($pri != '') ? $pri : $type;
            } else {
                $result['value'] = ($uni == '') ? $type : $type.','.$uni;
            }
            $this->entity->createDependence('entities/entitiescolumns', $result, false);
        }
    }
    private function getSyncConf ($exitingConf)
    {
        $res = [];
        $res['hasConfig'] = $this->entity->find(
            'id, name, app_dependence',
            ['FIND_IN_SET ('=>'name, "'.$exitingConf.'")'], 0, ['app_dependence'=>'asc','name'=>'asc'],true,false);

        $res['noConfig'] = $this->entity->find(
            'id, name, app_dependence',
            ['NOT FIND_IN_SET ('=>'name, "'.$exitingConf.'")'], 0,['app_dependence'=>'asc','name'=>'asc'],true,false);

        return $res;
    }
    private function getConfEntry ($entitiesConfigId, $data)
    {
        $queryCols = $this->entity->query('tableColumns',[$this->db->database, $data->tablename]);
        $result = [
            'entities_config_id' => $entitiesConfigId,
            'name'=>$data->name,
            'tablename'=>$data->tablename,
            'query_cols'=>$queryCols[0]['columns_names'],
            'query_order_by'=>'',
            'dependence_entities'=>$data->dependence_entities,
            'dependence_level' => $data->dependence_level
        ];
        return $result;
    }
    private function getNewElemnts()
    {
        $unusedTables = $this->entity->query('unusedTables',[$this->db->database, $this->config->item('sess_save_path')]);
        $unusedTables = explode(',',$unusedTables[0]['table_names']);
        $tablesWithConfigRes = [];
        $tablesNoConfigRes = [];

        foreach($unusedTables as $val){
            if( ! empty($val))
            {
                $query = $this->getTableDependence('tableIsDependence',[$this->db->database, $val]);

                if (is_array($query) && ! empty($query))
                {
                    foreach($query as $queryv){

                        $where = ['entities.tablename'=>$queryv, 'entities.entities_config_id'=>$this->post['entities_config_id']];
                        $select = 'entities_config.name as configName,entities.dependence_level';
                        $subquery = $this->entity->findWithDependence('entities',$select,$where);
                        $entitiyName = strtolower(str_replace('_','',$val));

                        if (is_array($subquery) && ! empty($subquery)) {
                            $tablesWithConfigRes[] = $this->getTableEntryWithConf($val, $subquery, $entitiyName );
                        } else {

                            $res = $this->getTableEntryNoConf($val, $entitiyName);

                            if ( ! empty($res)) {
                                $tablesNoConfigRes = array_merge($tablesNoConfigRes, $res);
                            }
                        }
                    }
                }
            }
        }
        if( ! empty($tablesWithConfigRes) && empty($tablesNoConfigRes)) {
            $entities = $tablesWithConfigRes;
        } else if( empty($tablesWithConfigRes) && ! empty($tablesNoConfigRes)) {
            $entities = $tablesNoConfigRes;
        } else {
            $entities = $this->mergeConfig($tablesWithConfigRes, $tablesNoConfigRes);
        }

        return $entities;
    }
    private function getTableEntryNoConf ($val, $entitiyName )
    {
        $depQuery = $this->getTableDependence('tableIsDependence',[$this->db->database, $val]);
        $res = [];

        if (is_array($depQuery) && ! empty($depQuery)) {
            foreach($depQuery as $depQueryv){
                $res[$depQueryv] = [
                    'config_id'=>$this->post['entities_config_id'],
                    'configName'=>'',
                    'name' => $entitiyName,
                    'tablename' => $val,
                    'dependence_level' => 0,
                    'dependence_entities' => '',
                    'isNewElement' => '1'
                ];
            }
        }

        return $res;
    }
    private function getTableEntryWithConf ($val, $subquery, $entitiyName )
    {
        $depEnt = $this->getTableDependence('tableHasDependencies',[$this->db->database, $val]);
        $res = [
            'config_id'=>$this->post['entities_config_id'],
            'configName'=>$subquery[0]['configName'],
            'name' => $entitiyName,
            'tablename' => $val,
            'dependence_level' => ($subquery[0]['dependence_level']+1),
            'dependence_entities' => implode(',',$depEnt),
            'isNewElement' => '1'
        ];
        return $res;
    }
    private function getTableDependence ($queryKey, $format)
    {
        $res = [];
        $query = $this->entity->query($queryKey, $format);

        if(is_array($query) && ! empty($query))
        {
            foreach($query as $row){
                if(! empty($row['tablename'])){ $res[] = $row['tablename']; }
            }
        }
        return $res;
    }
    private function getTableHasDependencies ($tablename = '')
    {
        $tablename = ($tablename == '') ? $this->post['tablename'] : $tablename;
        $query = $this->entity->query('tableHasDependencies',[$this->db->database, $tablename]);
        $res = [];

        if(is_array($query) && ! empty($query))
        {
            foreach($query as $row){
                if(! empty($row['tablename'])){
                    $res[] = $row;
                }
            }
        }
        return $res;
    }
    private function getFilteredResults ()
    {
        $findWhere = [];
        $countWhere = [];
        $limit = '';

        if(isset($this->modelFilter['entities_config_id']) && $this->modelFilter['entities_config_id'] != '')
        {
            $findWhere[] = 'entities_config_id='.$this->modelFilter['entities_config_id'];
            $countWhere['entities_config_id'] = $this->modelFilter['entities_config_id'];
        }
        if(isset($this->modelFilter['app_dependence']) && $this->modelFilter['app_dependence'] != '')
        {
            $findWhere[] = 'e.app_dependence='."'".$this->modelFilter['app_dependence']."'";
            $countWhere['app_dependence'] = $this->modelFilter['app_dependence'];
        }
        if (is_array($this->filteredPagination['limit']))
        {
            $limit .= " LIMIT ".implode(',',array_reverse($this->filteredPagination['limit']));
        } else {
            $limit .= " LIMIT ".$this->filteredPagination['limit'];
        }


        $this->entryCounts = $this->entity->getEntriesCount($countWhere,'entities');
        $this->setViewPagination();

        $findWhere = (! empty($findWhere)) ? " WHERE ".implode(' AND ',$findWhere) : '';

        return $this->buildResult($this->entity->query('entities', [$findWhere,$limit]));
    }
    private function getConfigFiles ()
    {
        $this->load->helper('directory');

        $pathArgs = ['third_party','user-bundle','config','entity'];
        $path = APPPATH.implode(DIRECTORY_SEPARATOR, $pathArgs);
        $res = directory_map($path.DIRECTORY_SEPARATOR);

        return $res;
    }
    private function viewAvailabelTables()
    {
        $copy = $this->entity->query('unusedTables',[$this->db->database, $this->config->item('sess_save_path')]);
        $copy = explode(',',$copy[0]['table_names']);
        $res = [];
        $unusedTable = [];
        $tablesWithConfig = [];
        $tablesWithConfigRes = [];
        $tablesNoConfig = [];

        foreach($copy as $val){
            if( ! empty($val))
            {
                $query = $this->entity->query('tableIsDependence',[$this->db->database, $val]);

                if (is_array($query) && ! empty($query)) {
                    $where = ['entities.tablename'=>$query[0]['tablename']];
                    $subquery = $this->entity->findWithDependence('entities','entities_config.id,entities_config.name',$where);
                    if (is_array($subquery) && ! empty($subquery)) {
                        $tablesWithConfigRes[$subquery[0]['id'].'/'.$val] = $subquery[0]['name'].' / '. $val;
                        $tablesWithConfig[$val] = ['id'=>$subquery[0]['id'],'name'=>$subquery[0]['name']];
                    }else {
                        $tablesNoConfig[$val] = $query[0]['tablename'];
                        $res[] = $val;
                    }
                } else {
                    $res[] = $val;
                }
            }
        }
        $tablesWithConfigKeys = array_keys($tablesWithConfig);
        if( ! empty($res))
        {
            foreach($res as $val){
                if (isset($tablesNoConfig[$val]) && in_array($tablesNoConfig[$val],$tablesWithConfigKeys))
                {
                    $tablesWithConfigRes[$tablesWithConfig[$tablesNoConfig[$val]]['id'].'/'.$val] = $tablesWithConfig[$tablesNoConfig[$val]]['name'].' / '. $val;

                } else {
                    $unusedTable[] = $val;
                }
            }
        }
        $this->view['unusedTables'] = $unusedTable;
        $this->view['tablesWithConfig'] = $tablesWithConfigRes;
    }
    private function createDbAllConf ()
    {
        $this->load->helper('directory');

        $pathArgs = ['third_party','user-bundle','config','entity'];
        $path = APPPATH.implode(DIRECTORY_SEPARATOR, $pathArgs);
        $map = $this->getConfigFiles();

        foreach($map as $file){
            $this->loadEntities($file,'');
        }

        $this->buildEntityEntries();
    }
    private function buildDepArgs ($data) {
        $args = [];
        foreach($data as $k=>$v){
            $args[] = $v["table_name"];
        }
        return $args;
    }
    private function buildMasterConf ($maintable,$lvl)
    {
        $query = $this->entity->query('tablesHasDependencies',[$this->db->database, $maintable]);
        $arr = [];
        $depres = '';
        $dep = '';

        if (is_array($query) &&  ! empty($query)) {
            $dep = $this->buildDepArgs ($query);
            $depres = implode(',',$dep);
        }

        $arr[] = ['name'=>$maintable, 'level'=>$lvl,'dep' =>$depres];
        $lvl += 1;

        if (is_array($dep) &&  ! empty($dep))
        {
            foreach($dep as $dk)
            {
                $depq = $this->buildMasterConf($dk,$lvl);

                if (is_array($depq) &&  ! empty($depq)){
                    $arr = array_merge($arr,$depq);
                }
            }
        }

        return $arr;
    }
    private function buildEntityEntries ()
    {
        $isOk = true;

        foreach($this->queryData as $key => $values)
        {
            $this->entity->create(['name' => $key]);
            $id = $this->entity->getLastInsertId();

            $isOk = $this->buildQueryData($id, $values);

            if( ! $isOk) {
                break;
            }
        }
        return $isOk;
    }
    private function buildResult ($results)
    {
        foreach($results as $k=>$v)
        {
            $results[$k]['columns'] = [];
            $results[$k]['queryDefined'] = [];
            $results[$k]['queryOrderBy'] = [];

            if ( ! empty($v['columnsIds'])) {
                $results[$k]['columns'] = $this->entity->findDependence('entities/entitiescolumns','',['id IN'=>'('.$v['columnsIds'].')'],0,[],true,false);
            }
            if ( ! empty($v['queryDefinedIds'])) {
                $results[$k]['queryDefined'] = $this->entity->findDependence('entities/entitiesquerydefined','',['id IN'=>'('.$v['queryDefinedIds'].')'],0,[],true,false);
            }
            if ( ! empty($v['query_order_by'])) {

                $ob = explode(',',$v['query_order_by']);

                foreach($ob as $obk){
                    $obk = explode('=',$obk);
                    $results[$k]['queryOrderBy'][trim($obk[0])] = trim($obk[1]);
                }
            }
        }

        return $results;
    }
    private function buildQueryData ($parentId, $queryData)
    {
        $isOk = true;

        foreach($queryData as $key => $val){

            $orderby = [];

            if ( ! empty($val['queryOrderBy'])){
                foreach($val['queryOrderBy'] as $k=>$v){
                    $orderby[] = $k.'='.$v;
                }
            }

            $data = [
                'entities_config_id' => $parentId,
                'name' => $key,
                'tablename' => $val['table'],
                'query_cols' => $val['queryCols'],
                'query_order_by' => implode(',',$orderby),
                'dependence_entities' => implode(',',$val['dependenceEntities']),
                'dependence_level' => $val['dependence_level']
            ];

            $this->entity->createDependence('entities',$data);

            $id = $this->entity->getDependenceLastInsertId('entities');

            if( ! empty($val['columns'])){

                foreach($val['columns'] as $ck=>$cv){
                    if (! empty($ck) && ! empty($cv)) {
                        $data = [];
                        $data['entities_id'] = $id;
                        $data['key'] = $ck;
                        $data['value'] = (is_array($cv)) ? implode(',',$cv): $cv;
                        $this->entity->createDependence('entities/entitiescolumns',$data);
                    }
                }
            }
            if( ! empty($val['queryDefined'])){
                foreach($val['queryDefined'] as $qk=>$qv){
                    $data = [];
                    $data['entities_id'] = $id;
                    $data['key'] = $qk;
                    $data['value'] = $qv;
                    $this->entity->createDependence('entities/entitiesquerydefined',$data);
                }
            }

            if( ! $isOk) {
                break;
            }
        }
        return $isOk;
    }
    private function loadEntities ($file, $dependence="", $dependenceLevel=0)
    {
        if($dependence == "")
        {
            $file = str_replace('.php','',$file);
            $this->config->load('entity'.DIRECTORY_SEPARATOR.$file,true);

            $entity = str_replace('config_','',$file);
            $fileName = 'config_'.$entity;

            $args = $this->config->item($entity,'entity'.DIRECTORY_SEPARATOR.'config_'.$entity);

            $this->queryData[$fileName] = [];
            $this->queryData[$fileName][$entity] = $args;
            $this->queryData[$fileName][$entity]['dependence_level'] = 0;
            $dependenceLevel = 1;

        } else {
            $entity = $dependence;
            $fileName = $file;
            $args = $this->config->item($entity,'entity'.DIRECTORY_SEPARATOR.$fileName);
            $this->queryData[$fileName][$entity] = $args;
            $this->queryData[$fileName][$entity]['dependence_level'] = $dependenceLevel;

            if($this->hasDependencies ($args))
            {
                $dependenceLevel+=1;
            }
        }

        $this->configEntities[$entity] = $args;
        $this->configEntities[$entity]['configName'] = $fileName;

        if($this->hasDependencies ($args))
        {
            foreach($args['dependenceEntities'] as $key){
                $this->loadEntities($file, $key, $dependenceLevel);
            }
        }
    }
    private function hasDependencies ($args)
    {
        return (is_array($args) && array_key_exists('dependenceEntities', $args) && ! empty($args['dependenceEntities']));
    }

    private function mergeConfig($tablesWithConfigRes, $tablesNoConfigRes)
    {
        $res = [];
        $tablesNoConfigKeys = array_keys($tablesNoConfigRes);
        $select = "entities_config.id as config_id, entities_config.name as configName, entities.name, entities.tablename, entities.dependence_entities, entities.dependence_level";
        $where = ['entities_config.id'=>$this->post['entities_config_id']];
        $orderBy = ['entities.dependence_level' => 'asc'];
        $entities = $this->entity->findWithDependence('entities', $select, $where, 0, $orderBy);
        $entities = array_merge($entities,$tablesWithConfigRes);

        if( ! empty($tablesNoConfigKeys) && ! empty($tablesNoConfigKeys))
        {
            foreach($entities as $k=>$v) {

                $lvlkey = 'lvl_'.$v['dependence_level'];

                if( ! array_key_exists($lvlkey,$res)){ $res[$lvlkey] = []; }
                $res[$lvlkey][]= $v;

                if(in_array($v['tablename'],$tablesNoConfigKeys))
                {
                    $deblvl = $v['dependence_level']+1;
                    $tablesNoConfigRes[$v['tablename']]['configName'] = $v['configName'];
                    $tablesNoConfigRes[$v['tablename']]['dependence_level'] = $deblvl;
                    $lvlkey = 'lvl_'.$deblvl;
                    if( ! array_key_exists($lvlkey,$res)){  $res[$lvlkey] = [];  }
                    $res[$lvlkey][]= $tablesNoConfigRes[$v['tablename']];
                }
            }
            ksort($res);
        }
        $entities = [];

        if ( ! empty($res)) {
            foreach($res as $resK){ foreach($resK as $resKk=>$resV){
                if(array_key_exists('isNewElement',$resV)) {
                    $entities[] = $resV;
                }
            } }
        }

        return $entities;
    }
    private function getConfTemplate ($entry)
    {
        $columns = [];
        $queryDefined = '[]';
        $dep = [];
        $orderBy = '';

        if ( ! empty($entry['query_order_by'])) {
            $orderByRes = [];
            $orderBy = explode(',', $entry['query_order_by']);
            foreach($orderBy as $k) {
                $orderByArgs = explode('=', $k);
                $orderByRes[]= '
        "'.$orderByArgs[0].'"=>"'.$orderByArgs[1].'"';
            }
            $orderBy = implode(',',$orderByRes);
        }
        foreach($entry['columns'] as $k=>$v) {
            $columns[] = '
        "'.$v['key'].'"=>"'.$v['value'].'"';
        }
        $columns = implode(',',$columns);
        if ( ! empty($entry['queryDefined'])) {

            $queryDefined = [];

            foreach($entry['queryDefined'] as $k=>$v) {
                $queryDefined[] = '
        "'.$v['key'].'"=>"'.$v['value'].'"';
            }
            $queryDefined = '['.implode(',',$queryDefined).'
    ]';
        }
        if($entry['dependence_entities'] != '') {
            $dep = explode(',',$entry['dependence_entities']);
            $entry['dependence_entities'] = "'".implode("','",$dep)."'";
        }
        $tpl = '
/* '.$entry['configName'].' / '.$entry['tablename'].' */
$config["'.$entry['name'].'"] = [
    "table" => "'.$entry['tablename'].'",
    "columns" => ['.$columns.'
    ],
    "queryCols" => "'.$entry['query_cols'].'",
    "queryOrderBy" => ['.$orderBy.'],
    "dependenceEntities" => ['.$entry['dependence_entities'].'],
    "queryDefined" => '.$queryDefined.'
];
';
        return $tpl;
    }
}