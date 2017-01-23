<?php
/**
 * Created by PhpStorm.
 * Date: 30.09.2016
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class Usersmodel extends MY_Model
{
    public function __construct()
    {
        parent::__construct('userentity');

        $this->filterProperty = [
            'confirmation' => [
                '1'=>'Aktive User',
                '0'=>'Unbestätigte User'
            ]
        ];
    }
    public function add ()
    {
        $this->view['secureRoles'] = $this->routemanager->getSecureRoles();
    }
    public function all ()
    {
        if ( ! empty($this->modelFilter))
        {
            $this->view['users'] = $this->getFilteredUsers();
        } else {

            $this->entryCounts = $this->entity->getEntriesCount();
            $this->setViewPagination ($this->configPagination["limit"]["limitmax"], $this->configPagination["limit"]["limitstep"]);

            $this->view['users'] = $this->entity->find('', [], $this->configPagination["limit"]["limitmax"]);
        }

        $this->view['filter']['confirmation'] = $this->filterProperty['confirmation'];
        $this->view['confirmation'] = (isset($this->modelFilter['confirmation'])) ? $this->modelFilter['confirmation'] : '';
        $this->view['secureRoles'] = $this->routemanager->getSecureRoles();
    }
    public function create ()
    {
        $this->load->library('manager/passwordmanager');
        $this->post['password'] = $this->passwordmanager->anonymPassword();
        $this->entity->create($this->post);
    }
    public function update ()
    {
        parent::update();

        if (isset($this->post['confirmation'])) {
            $where = ['user_id' => $this->post['id'],'confirmation_type' => 'signup'];
            $this->entity->deleteDependence('userconfirmation',$where);
        }
    }
    private function getFilteredUsers ()
    {
        $where = [];

        if(isset($this->modelFilter['confirmation']) && $this->modelFilter['confirmation'] != '')
        {
            $where['confirmation'] = $this->modelFilter['confirmation'];
        }

        $this->entryCounts = $this->entity->getEntriesCount($where);
        $this->setViewPagination();

        return $this->entity->find( '', $where, $this->filteredPagination['limit']);
    }
    private function dummy ()
    {
        $username = 'ubtester';
        $email = '@ubtester.de';
        $start = 3;
        $max = 198;

        while ($start < $max)
        {
            $data = [
                'username' => $username.$start,
                'password' => $username.$start,
                'email' => $username.$start.$email,
                'role' => 'user'
            ];

            $this->entity->create($data, false);

            $start++;
        }
    }
}