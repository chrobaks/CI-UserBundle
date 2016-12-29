<?php
/**
 * Created by PhpStorm.
 * Date: 24.09.2016
 */
defined('BASEPATH') OR exit('No direct script access allowed');

// entities entity
$config['entitiesentity'] = [
    'table' => 'entities_config',
    'columns' => [
        'id'=>['PRIMARY_KEY'],
        'name'=>['VARCHAR','UNIQUE']
    ],
    'queryCols' => "id,name",
    'queryOrderBy' => ['name'=>'asc'],
    'dependenceEntities' => ['entities'],
    'queryDefined' => [
        'entities' => "
            SELECT DISTINCT ec.id as config_id, ec.name as configName , e.*,
            (SELECT group_concat(id) FROM entities_columns WHERE entities_columns.entities_id=e.id) as columnsIds,
            (SELECT group_concat(id) FROM entities_query_defined WHERE entities_query_defined.entities_id=e.id) as queryDefinedIds
            FROM entities_config ec
            LEFT JOIN entities e ON e.entities_config_id=ec.id
            %s
            ORDER BY ec.name asc, e.dependence_level asc
            %s",
        'unusedTables' => "
            SELECT DISTINCT group_concat(table_name) as table_names
            FROM information_schema.tables
            WHERE table_schema = '%1\$s'
            AND table_name COLLATE utf8_general_ci NOT IN (
                (SELECT concat(tablename) FROM %1\$s.entities)
            )
            AND table_name COLLATE utf8_general_ci != '%2\$s'
            ORDER BY table_name",
        'tableColumns' => "
            SELECT group_concat(COLUMN_NAME) as columns_names
            FROM INFORMATION_SCHEMA.COLUMNS
            WHERE table_schema COLLATE utf8_general_ci = '%s'
            AND table_name COLLATE utf8_general_ci = '%s'",
        'tableConfiguration' => "
            SELECT ec.id
            FROM %1\$s.entities_config ec
            LEFT JOIN %1\$s.entities e ON e.entities_config_id = ec.id
            WHERE FIND_IN_SET ( e.tablename  COLLATE utf8_general_ci,
            (SELECT GROUP_CONCAT(REPLACE(COLUMN_NAME,'_id',''))
            FROM INFORMATION_SCHEMA.COLUMNS
            WHERE table_schema COLLATE utf8_general_ci = '%1\$s'
            AND table_name COLLATE utf8_general_ci = '%2\$s'
            AND  FIND_IN_SET (COLUMN_NAME COLLATE utf8_general_ci,(SELECT DISTINCT GROUP_CONCAT( CONCAT_WS
            ( '_', tablename, 'id' ) ) FROM %1\$s.entities))))",
        'tableHasDependencies' => "
            SELECT DISTINCT GROUP_CONCAT(table_name) AS tablename
            FROM INFORMATION_SCHEMA.COLUMNS
            WHERE table_schema COLLATE utf8_general_ci = '%1\$s'
            AND COLUMN_NAME COLLATE utf8_general_ci = CONCAT_WS( '_', '%2\$s', 'id')",
        'tablesHasDependencies' => "
            SELECT DISTINCT table_name
            FROM INFORMATION_SCHEMA.COLUMNS
            WHERE table_schema COLLATE utf8_general_ci = '%1\$s'
            AND FIND_IN_SET (COLUMN_NAME COLLATE utf8_general_ci,(
            SELECT DISTINCT group_concat( CONCAT_WS( '_', table_name, 'id') ) AS table_names
            FROM information_schema.columns
            WHERE table_schema COLLATE utf8_general_ci = '%1\$s'
            AND FIND_IN_SET(table_name COLLATE utf8_general_ci,'%2\$s')))",
        'tableIsDependence' => "
            SELECT DISTINCT table_name AS tablename
            FROM INFORMATION_SCHEMA.COLUMNS
            WHERE table_schema COLLATE utf8_general_ci = '%1\$s'
            AND FIND_IN_SET (table_name COLLATE utf8_general_ci,(
            SELECT DISTINCT GROUP_CONCAT( REPLACE( COLUMN_NAME, '_id', ''))
            FROM INFORMATION_SCHEMA.COLUMNS
            WHERE table_schema COLLATE utf8_general_ci = '%1\$s'
            AND table_name COLLATE utf8_general_ci = '%2\$s'
            AND COLUMN_NAME COLLATE utf8_general_ci LIKE ('%%_id')))",
        'deleteConfig' => "
          DELETE ec.*, e.*, ecl.* FROM entities_config ec, entities e, entities_columns ecl
          WHERE ec.id = %d AND e.entities_config_id = ec.id AND ecl.entities_id = e.id",
        'syncConf' => "
            SELECT
            (SELECT DISTINCT GROUP_CONCAT(CONCAT_WS('/',name,app_dependence)) FROM entities_config WHERE NOT FIND_IN_SET(name,'%1\$s')) as noConfig,
            (SELECT DISTINCT GROUP_CONCAT(CONCAT_WS('/',name,app_dependence)) FROM entities_config WHERE FIND_IN_SET(name,'%1\$s')) as hasConfig
            FROM entities_config LIMIT 1",
        'updateAppDependence' => "
            UPDATE entities_config ec , entities e
            SET ec.app_dependence = IF( ec.app_dependence='public', 'protected', 'public'),
            e.app_dependence = IF( e.app_dependence='public', 'protected', 'public')
            WHERE ec.name = '%s' AND e.entities_config_id=ec.id;"
    ]
];

// entities entity
$config['entities'] = [
    'table' => 'entities',
    'columns' => [
        'id' => ['PRIMARY_KEY'],
        'entities_config_id' => ['INTEGER'],
        'name' => ['VARCHAR','UNIQUE'],
        'tablename' => ['VARCHAR'],
        'query_cols' => ['TEXT'],
        'query_order_by' => ['TEXT'],
        'dependence_entities' => ['TEXT'],
        'dependence_level' => ['INTEGER']
    ],
    'queryCols' => "id,name,tablename,query_cols,query_order_by,dependence_entities",
    'queryOrderBy' => ['name'=>'asc'],
    'queryDefined' => [],
    'dependenceEntities' => ['entitiescolumns','entitiesquerydefined']
];

// entitiescolumns entity
$config['entitiescolumns'] = [
    'table' => 'entities_columns',
    'columns' => [
        'id'=>['PRIMARY_KEY'],
        'entities_id'=>['INTEGER','INDEX'],
        'key'=>['VARCHAR'],
        'value'=>['TEXT']
    ],
    'queryCols' => "id,entities_id,key,value",
    'queryOrderBy' => [],
    'queryDefined' => [],
    'dependenceEntities' => []
];

// entitiesquerydefault entity
$config['entitiesquerydefined'] = [
    'table' => 'entities_query_defined',
    'columns' => [
        'id'=>['PRIMARY_KEY'],
        'entities_id'=>['INTEGER','INDEX'],
        'key'=>['VARCHAR'],
        'value'=>['TEXT']
    ],
    'queryCols' => "id,entities_id,key,value",
    'queryOrderBy' => [],
    'queryDefined' => [],
    'dependenceEntities' => []
];