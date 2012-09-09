<?php
/**
 * 
 * @author Facundo Capua (facundocapua@gmail.com)
 * 
 * @method Database		getInstance
 *
 */
class Database extends BaseSingleton{
    const DATE_FORMAT = 'Y-m-d';
    const DATETIME_FORMAT = 'Y-m-d H:i';
    
    private static $_conn = null;
    
    public function __construct()
    {
        #self::$_conn = mysql_connect(DB_HOST,DB_USER,DB_PASS) or trigger_error(mysql_error(),E_USER_ERROR);
        #mysql_select_db(DB_NAME);
        #date_default_timezone_set("Europe/Madrid");
        #mysql_query("SET time_zone='+2:00'");
        #mysql_set_charset('utf8', self::$_conn);
        self::$_conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if(self::$_conn->connect_error){
            trigger_error('Connect Error ('.self::$_conn->connect_errno.') '.self::$_conn->connect_error, E_USER_ERROR);    
        }
        
        mysqli_set_charset(self::$_conn, 'utf8');
    }
    
    public function __destruct()
    {
        self::$_conn->close();
    }
    
    public function query($query)
    {
    	$recordset = array();
    	$r = self::$_conn->query($query) or trigger_error( ' [ '.$query.' ]' . self::$_conn->error, E_USER_ERROR );
    	if(!is_bool($r) && mysqli_num_rows($r) > 0){
            while( $aux = mysqli_fetch_assoc($r) ){
                $recordset[] = $aux;   
    		}
    		mysqli_free_result($r);
    	}
    	
    	return $recordset;
    }
    
    public function multiQuery($queries)
    {
        if(is_array($queries)){
            $queries = implode(';', $queries);
        }
        
        $recordsets = array();
        if(self::$_conn->multi_query($queries)){
            do{
                if($result = self::$_conn->store_result()){
                    $recordset = array();
                    while($aux = $result->fetch_assoc()){
                        $recordset[] = $aux;
                    }
                    $recordsets[] = $recordset;
                    $result->free();
                }
                
            }while(self::$_conn->next_result());
        }
        
        return $recordsets;
    }
    
    public function listFields($table)
    {
        $return = "'' x ";
        $r = $this->query("SHOW FIELDS FROM $table ");
        foreach ( $r as $rs ){
            if(! empty($rs['Key']))
                continue; #No keys
            $return .= " , '" . ($rs['Default'] == '(NULL)' ? '' : $rs['Default']) . "' `" . $rs['Field'] . "` ";
        }
        
        return $return;
    }
    
    function getAdmin($id)
    {
    	$return = '';
        
        if( $rs = $this->query("select first_name, last_name from administrator where id = '$id' ") ){
    	    $return = $rs[0]['first_name']. ' ' . $rs[0]['last_name'] ;   
    	}
    	
    	return $return;
    }
    
    function getOrden($table, $where = '', $order = ORDEN_ARRIBA)
    {
        $rs = $this->query("select ifnull( " . ($order == ORDEN_ABAJO ? 'max' : 'min') . "(orden) " . ($order == ORDEN_ABAJO ? '+' : '-') . " 1, 0) orden from  {$table} {$where} ");
        
        return $rs[0]['orden'];
    }
}