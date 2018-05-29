<?php
Doo::loadModel('AppModel');

class  AdVideoBase extends AppModel{

    /**
     * @var int Max length is 11.
     */
    public $id;

    /**
     * @var int Max length is 11.
     */
    public $ad_product_id;

    /**
     * @var varchar Max length is 100.
     */
    public $product_name;

    /**
     * @var varchar Max length is 200.
     */
    public $video_name;

    /**
     * @var varchar Max length is 300.
     */
    public $video_url;

    /**
     * @var tinyint Max length is 2.
     */
    public $ad_type;

    /**
     * @var tinyint Max length is 2.
     */
    public $ad_subtype;

    /**
     * @var int Max length is 2.
     */
    public $platform;
    
    /**
     * @var float
     */
    public $screen_ratio;
    
    /**
     * @var tinyint Max length is 2.
     */
    public $ischeck;

    /**
     * @var varchar Max length is 100.
     */
    public $checker;

    /**
     * @var varchar Max length is 100.
     */
    public $owner;

    /**
     * @var varchar Max length is 100.
     */
    public $check_msg;

    /**
     * @var int Max length is 11.
     */
    public $creattime;

    /**
     * @var int Max length is 11.
     */
    public $updatetime;

    public $_table = 'ad_video';
    public $_primarykey = 'id';
    public $_fields = array('id','ad_product_id','product_name','video_name','video_url','ad_type','ad_subtype','screen_ratio','platform','ischeck','checker','owner','check_msg','creattime','updatetime');

    public function getVRules() {
        return array(
                'id' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'optional' ),
                ),

                'ad_product_id' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'product_name' => array(
                        array( 'maxlength', 100 ),
                        array( 'notnull' ),
                ),

                'video_name' => array(
                        array( 'maxlength', 200 ),
                        array( 'notnull' ),
                ),

                'video_url' => array(
                        array( 'maxlength', 300 ),
                        array( 'optional' ),
                ),

                'ad_type' => array(
                        array( 'integer' ),
                        array( 'maxlength', 2 ),
                        array( 'optional' ),
                ),

                'ad_subtype' => array(
                        array( 'integer' ),
                        array( 'maxlength', 2 ),
                        array( 'optional' ),
                ),

                'platform' => array(
                        array( 'integer' ),
                        array( 'maxlength', 2 ),
                        array( 'optional' ),
                ),
                'screen_ratio' => array(
                        array( 'float' ),
                        array( 'optional' ),
                ),
                'ischeck' => array(
                        array( 'integer' ),
                        array( 'maxlength', 2 ),
                        array( 'notnull' ),
                ),

                'checker' => array(
                        array( 'maxlength', 100 ),
                        array( 'optional' ),
                ),

                'owner' => array(
                        array( 'maxlength', 100 ),
                        array( 'optional' ),
                ),

                'check_msg' => array(
                        array( 'maxlength', 100 ),
                        array( 'optional' ),
                ),

                'creattime' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'optional' ),
                ),

                'updatetime' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'optional' ),
                )
            );
    }

}