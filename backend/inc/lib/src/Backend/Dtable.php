<?php
namespace Backend;
//error_reporting(0);
/**
 * Script:    DataTables PDO server-side script for PHP and MySQL
 * CopyLeft: March 2016 - wildantea, wildantea.com
 * Email : wildannudin@gmail.com
 **/
class DTable extends Database
{
    private $total_filtered;
    private $record_total;
    private $offset;
    private $data = array();
    private $request;
    private $search_request;
    private $is_numbering = 0;
    private $order_by = array();
    private $group_by="";
    private $disable_search = array();
    private $callback = array();
    public $debug = 0;
    public $debug_sql="";
    public $main_table = "";
    public $from_query = "";

     function __construct($host,$port,$db_username,$db_password,$db_name)
    {
        parent::__construct($host,$port,$db_username,$db_password,$db_name);
    }

    //filter data
    public function getColumn($col)
    {
        $col = array_diff($col, $this->getDisableSearch());
        foreach ($col as $key) {
            $keys   = $key . " LIKE ?";
            $mark[] = $keys;
        }

        $im = implode(' OR  ', $mark);
        return $im;
    }

    public function getValue($col, $value)
    {
        $col = array_diff($col, $this->getDisableSearch());
        foreach ($col as $key) {
            $val      = '%' . $value . '%';
            $result[] = $val;
        }

        return $result;
    }


    public function resultData($sql,$prepare_data=null)
    {
        $result = $this->query($sql,$prepare_data);
        return $result;
    }

    public function getBetween($content,$start,$end){
        $r = explode($start, $content);
        if (isset($r[1])){
            $r = explode($end, $r[1]);
            return 'from '.$r[0];
        }
            return '';
    }

    /**
     * exclude column searching datatable
     * @param array $data array column 
     */
    public function setDisableSearchColumn() {
        $data = func_get_args();
        $this->disable_search = $data;
    }
    public function getDisableSearch() {
       return $this->disable_search;
    }

    /**
     * set from query, in case you have sub query use this
     * @param [type] $query [description]
     */
    public function setFromQuery($query) {
        $this->from_query = $query;
    }
    public function getFromQuery() {
        return $this->from_query;
    }

    function stringAfter($str,$word) {
        $str = str_ireplace($word, strtolower($word), $str);
        return substr( $str, strrpos( $str, 'from' ) );
    }

    public function setTotalRecord($sql_for_counting,$type_record,$prepare_data=null)
    {
        $from = "";
        if ($this->getFromQuery()!='') {
            $from = "from";
        }
        if ($this->group_by!="") {
            $group_by = " group by ".$this->getGroupBy();
            $count_data = $this->fetchCustomSingle("select count(DISTINCT ".$this->getGroupBy().") as jml $from ".$sql_for_counting,$prepare_data);
            $query_count = "select count(DISTINCT ".$this->getGroupBy().") as jml $from ".$sql_for_counting;
            
        } else {
            $count_data = $this->fetchCustomSingle("select count(*) as jml $from ".$sql_for_counting,$prepare_data);
            $query_count = "select count(*) as jml $from ".$sql_for_counting;
        }

        //echo $query_count;

       // echo $this->getErrorMessage();

            if ($this->debug==1) {
                $this->setCallback(array('error_count' => $this->getErrorMessage(),'count_query' => $query_count));
            }
            $data_count = $count_data->jml;
            if ($type_record=='total') {
                $this->record_total = $data_count;   
            } else {
                $this->total_filtered = $data_count;
            }
    }

    public function getTotalRecord($type_record=NULL)
    {
        if ($type_record=='total') {
            return $this->record_total;
        } else {
            return $this->total_filtered;
        }
    }


    public function joinValue($search_value,$where_data=array())
    {

        if ($where_data!=null) {
            $where_data = array_values($where_data);
        } else {
            $where_data = array();
        }
        $res = array_merge($where_data,$search_value);
        return $res;
    }


    //create numbering column
    public function number($number)
    {
        $requestData   = $_REQUEST['start']+$number;
        return $requestData;

    }

    public function setNumberingStatus($status) {
         $this->is_numbering = $status;
    }
    
    public function setOrderBy()
    {
        $arg_list = func_get_args();
        $this->order_by = $arg_list;
    }

    public function getOrderBy($columns)
    {

        $do_order = "";
        if (count($this->order_by)>0) {
            foreach ($this->order_by as $column_name) {
            }
            $all_order_by = implode(",", $this->order_by);
            $last_order_by = ", ".$column_name;
            $do_order = "ORDER BY ".$all_order_by;
        } /*else {
            $last_order_by = "";
        }*/

        if (isset($this->request['order']) && $this->is_numbering==false) {
            $do_order_by = "ORDER BY ".$columns[$this->request['order'][0]['column']];
            $do_order_type = $this->request['order'][0]['dir'];
            $do_order = $do_order_by.' '.$do_order_type;
         } elseif (isset($this->request['order']) && $this->is_numbering==true && $this->request['order'][0]['column']!=0) {
            $do_order_by = "ORDER BY ".$columns[$this->request['order'][0]['column']-1];
            $do_order_type = $this->request['order'][0]['dir'];
            $do_order = $do_order_by.' '.$do_order_type;
         }

         return $do_order;
        
    }

    public function setGroupBy($val)
    {
        $this->group_by = $val;
    }

    public function getGroupBy()
    {
        return $this->group_by;
    }


    //custom query datatable
    public function execQuery($sql, $columns,$prepare_data=array())
    {

        if ($prepare_data!==null) {
        $prepare_data=array_values($prepare_data);
        }

        //all data request
        $requestData   = $_REQUEST;
        $this->request = $requestData;


             $offset       = $requestData['start'];
             $offsets      = $offset ? $offset : 0;
             $this->offset = $offsets;

             $sql_for_counting = $sql;

             //print_r($requestData);

             $do_order = $this->getOrderBy($columns);


        if (!empty($requestData['search']['value'])) {

            $this->search_request  = $requestData['search']['value'];

            $after_remove = preg_replace('#\((([^()]+|(?R))*)\)#', "", $sql);

            if (strpos(strtolower($after_remove), "where")) {
                $condition = "and";
            } elseif (strpos(strtolower($after_remove), "having")) {
                $condition = "and";
            } else {
                $condition = "where";
            }



              //get search value
            $search_value = $this->getValue($columns, $this->search_request);

            //join search with where data and extract where data value
            $join = $this->joinValue($search_value,$prepare_data);

            $sql .= " $condition (" . $this->getColumn($columns).")";
            

            if ($this->getFromQuery()!='') {
                $sql_filtered = $this->getFromQuery()." $condition (" . $this->getColumn($columns).")";
                //$sql_filtered = $this->stringAfter($sql_filtered, 'from');
                $sql_filter_all = $this->getFromQuery();
            } else {
                $sql_filtered = $this->stringAfter($sql, 'from');
                $sql_filter_all = $this->stringAfter($sql_for_counting, 'from');
            }

            $this->setTotalRecord($sql_filter_all,'total',$prepare_data);
            //set total filtered
            $this->setTotalRecord($sql_filtered,'filtered',$join);

            $this->total_filtered = $this->getTotalRecord('filtered');

            if ($this->group_by!="") {
                $sql .= " group by ".$this->getGroupBy();
            }

            $sql .= " ".$do_order;



            
            if($requestData['length']<0) {
                $length = "";
            } else {
                $length = " LIMIT " . $requestData['start'].",".$requestData['length'];
            }
            $sql .= ' '.$length;

            if ($this->debug==1) {
                $this->setCallback(array('error_result' => $this->getErrorMessage(),'result_query' => $sql));
            }

           // echo $sql;
            $result = $this->resultData($sql,$join);


        } else {

            if ($this->getFromQuery()!='') {
                $sql_filtered = $this->getFromQuery();
                $sql_filter_all = $sql_filtered;
            } else {

                $sql_filtered = $this->stringAfter($sql, 'from');
                $sql_filter_all = $sql_filtered;
            }


            $this->setTotalRecord($sql_filter_all,'total',$prepare_data);
            $this->total_filtered = $this->getTotalRecord('total');


            if ($this->group_by!="") {
                $sql .= " group by ".$this->getGroupBy();
            }

            $sql .= " ".$do_order;



            //$result = $sql;

            if($requestData['length']<0) {
                $length = "";
            } else {
                $length = " LIMIT " . $requestData['start'].",".$requestData['length'];
            }
            $sql .= $length;


        

            $result = $this->resultData($sql,$prepare_data);

            if ($this->debug==1) {
                $this->setCallback(array('error_result' => $this->getErrorMessage(),'result_query' => $sql));
            }

        }

        //$data = $this->table_data($result,$columns);
        //
        return $result;
    }
    /**
     * set additional callback datatable
     * @param array $callback two dimensional array
     */
    public function setCallback($callback) {
        $this->callback = array_merge($this->callback,$callback);
    }
    public function getCallback() {
        return $this->callback;
    }

    public function setDebug($debug) {
        $this->debug = $debug;
    }

    public function setData($data)
    {
        $this->data = $data;
    }
    
    public function createData()
    {
        $data      = $this->data;
        $json_data = array(
            "draw" => intval($this->request['draw']),
            "recordsTotal" => intval($this->record_total),
            "recordsFiltered" => intval($this->total_filtered),
            "data" => $data // total data array
        );
        if (!empty($this->getCallback())) {
            $json_data = array_merge($this->getCallback(),$json_data); 
        }
        echo json_encode($json_data);
        // send data as json format
    }

}

?>