<?php
Doo::loadModel('AppModel');

class  AnalysisAppDeviceBase extends AppModel{

    /**
     * @var int Max length is 11.
     */
    public $id;

    /**
     * @var date
     */
    public $date;

    /**
     * @var varchar Max length is 128.
     */
    public $appkey;

    /**
     * @var int Max length is 11.
     */
    public $active;

    /**
     * @var int Max length is 11.
     */
    public $startup;

    /**
     * @var int Max length is 11.
     */
    public $effective;

    /**
     * @var int Max length is 11.
     */
    public $ad_arrived;

    /**
     * @var varchar Max length is 128.
     */
    public $channel;

    /**
     * @var varchar Max length is 32.
     */
    public $app_version;

    /**
     * @var varchar Max length is 32.
     */
    public $device_model;

    /**
     * @var varchar Max length is 32.
     */
    public $device_brand;

    /**
     * @var varchar Max length is 32.
     */
    public $resolution;

    public $_table = 'analysis_app_device';
    public $_primarykey = 'id';
    public $_fields = array('id','date','appkey','active','startup','effective','ad_arrived','channel','app_version','device_model','device_brand','resolution');

    public function __construct($properties = null) {
        parent::__construct($properties);
        Doo::db()->reconnect("statis");
    }

    public function getVRules() {
        return array(
                'id' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'optional' ),
                ),

                'date' => array(
                        array( 'date' ),
                        array( 'notnull' ),
                ),

                'appkey' => array(
                        array( 'maxlength', 128 ),
                        array( 'notnull' ),
                ),

                'active' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'startup' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'effective' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'ad_arrived' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'channel' => array(
                        array( 'maxlength', 128 ),
                        array( 'notnull' ),
                ),

                'app_version' => array(
                        array( 'maxlength', 32 ),
                        array( 'notnull' ),
                ),

                'device_model' => array(
                        array( 'maxlength', 32 ),
                        array( 'notnull' ),
                ),

                'device_brand' => array(
                        array( 'maxlength', 32 ),
                        array( 'notnull' ),
                ),

                'resolution' => array(
                        array( 'maxlength', 32 ),
                        array( 'notnull' ),
                )
            );
    }

}