<?php

  if ( ! defined('BASE_PATH')) exit('Access Denied!');

  /**
   *
   * Theme_Dao_Series
   * @author tiansh
   *
   */
  class Theme_Dao_Series extends Common_Dao_Base {

      protected $_name = 'theme_series';
      protected $_primary = 'id';

      /**
       *
       * @return multitype:
       */
      public function getAllSeries() {
          $sql = sprintf('SELECT * FROM %s WHERE 1 ORDER BY id DESC' , $this -> getTableName());

          return Db_Adapter_Pdo::fetchAll($sql);
      }

      /**
       *
       * 获取分页列表数据
       * @param array $params
       * @param int $page
       * @param int $limit
       */
      public function getList($start = 0 , $limit = 20 , $params = array()) {
          $where = Db_Adapter_Pdo::sqlWhere($params);
          $sql = sprintf('SELECT * FROM %s WHERE 1 AND %s ORDER BY id DESC LIMIT %d,%d' , $this -> getTableName() , $where , intval($start) , intval($limit));
          return Db_Adapter_Pdo::fetchAll($sql , $params);
      }

  }