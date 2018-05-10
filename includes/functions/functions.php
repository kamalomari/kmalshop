<?php
/*========================================*/
/*  encrypted & decrypted  Function v1.0 */
/* Function encrept and decrypt string*/
function my_simple_crypt( $string, $action = 'e' ) {
    // you may change these values to your own
    $secret_key = 'my_simple_secret_key';
    $secret_iv = 'my_simple_secret_iv';

    $output = false;
    $encrypt_method = "AES-256-CBC";
    $key = hash( 'sha256', $secret_key );
    $iv = substr( hash( 'sha256', $secret_iv ), 0, 16 );

    if( $action == 'e' ) {
        $output = base64_encode( openssl_encrypt( $string, $encrypt_method, $key, 0, $iv ) );
    }
    else if( $action == 'd' ){
        $output = openssl_decrypt( base64_decode( $string ), $encrypt_method, $key, 0, $iv );
    }

    return $output;
}
//$encrypted = my_simple_crypt( 'Aplicat123', 'e' );
//$decrypted = my_simple_crypt( $encrypted, 'd' );

/*===================================*/
/*    Get Categories Function v1.0   */
/* Function to get categories from DB*/
function getCat() {

    global $con;

    $getCats = $con->prepare("SELECT * FROM categories ORDER BY ID DESC");

    $getCats->execute();

    $cats = $getCats->fetchAll();

    return $cats;

}
/*================================*/
/*    Get Items Function v1.0    */
/* Function to get Items from DB*/
function getItem($CatID) {

    global $con;

    $getItems = $con->prepare("SELECT * FROM items WHERE Cat_ID = ? ORDER BY Item_ID DESC");

    $getItems->execute(array($CatID));

    $items = $getItems->fetchAll();

    return $items;

}
/*
** Check If User Is Not Activated
** Function To Check The RegStatus Of The User
*/

function checkUserStatus($user) {

    global $con;

    $stmtx = $con->prepare("  SELECT 
                                            Username, RegStatus 
                                        FROM 
                                            users 
                                        WHERE 
                                            Username = ? 
                                        AND 
                                            RegStatus = 0");

    $stmtx->execute(array($user));

    $status = $stmtx->rowCount();

    return $status;

}
	/*
	** Title Function v1.0
	** Title Function That Echo The Page Title In Case The Page
	** Has The Variable $pageTitle And Echo Defult Title For Other Pages
	*/
	function getTitle() {

		global $pageTitle;

		if (isset($pageTitle)) {

			echo $pageTitle;

		} else {

			echo 'Default';

		}
	}

	/*
	** Home Redirect Function v1.5
	** This Function Accept Parameters
	** $theMsg = Echo The Message [ Error | Success | Warning ]
	** $url = The Link You Want To Redirect To
	** $seconds = Seconds Before Redirecting
	*/
/**
 * @param $theMsg
 * @param null $url
 * @param int $seconds
 */
function redirectHome($theMsg, $url = null, $seconds = 3, $subS = 0) {

		if ($url === null) {

			$url = 'index.php';

			$link = 'Homepage';

		}elseif ($url === "back"){

			if (isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] !== '') {

				$url = $_SERVER['HTTP_REFERER'];

				$link = 'Previous Page';

			} else {

				$url = 'index.php';

				$link = 'Homepage';

			}
		}else{
		    $url = $url;
		    $link = substr($url, 0, $subS);
        }

		echo $theMsg;

		echo "<div class='container'><div class='alert alert-info'>You Will Be Redirected to $link After $seconds Seconds.</div></div> ";

		header("refresh:$seconds;url=$url");

		exit();

	}
	/*
	** Check Items Function v1.0
	** Function to Check Item In Database [ Function Accept Parameters ]
	** $select = The Item To Select [ Example: user, item, category ]
	** $from = The Table To Select From [ Example: users, items, categories ]
	** $value = The Value Of Select [ Example: Osama, Box, Electronics ]
	*/

	function checkItem($select, $from, $value) {

		global $con;

		$statement = $con->prepare("SELECT $select FROM $from WHERE $select = ?");

		$statement->execute(array($value));

		$count = $statement->rowCount();

		return $count;

	}
	/*
	** Count Number Of Items Function v1.0
	** Function To Count Number Of Items Rows
	** $item = The Item To Count
	** $table = The Table To Choose From
	*/

	function countItems($item, $table) {

		global $con;

		$stmt2 = $con->prepare("SELECT COUNT($item) FROM $table ");

		$stmt2->execute();

		return $stmt2->fetchColumn();

	}
	/*
	** Get Latest Records Function v1.0
	** Function To Get Latest Items From Database [ Users, Items, Comments ]
	** $select = Field To Select
	** $table = The Table To Choose From
	** $order = The Desc Ordering
	** $limit = Number Of Records To Get
	*/

	function getLatest($select, $table, $order, $limit = 5) {

		global $con;

		$getStmt = $con->prepare("SELECT $select FROM $table ORDER BY $order DESC LIMIT $limit");

		$getStmt->execute();

		$rows = $getStmt->fetchAll();

		return $rows;

	}
	/*
	** Get All Function v2.0
	** Function To Get All Records From Any Database Table
	*/

	function getAllFrom($field, $table, $where = NULL, $and = NULL, $orderfield, $ordering = "DESC") {

		global $con;

		$getAll = $con->prepare("SELECT $field FROM $table $where $and ORDER BY $orderfield $ordering");

		$getAll->execute();

		$all = $getAll->fetchAll();

		return $all;

	}
    /*
    ** Get Randim Password
    ** Function To Get Password random 10 letter
    */
    function randomPassword() {
        $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $pass = array(); //remember to declare $pass as an array
        $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
        for ($i = 0; $i < 10; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        return implode($pass); //turn the array into a string
    }
    /*
	** Get IP Address from user V1.0
	** Function To Get IP Address
	*/
function get_client_ip() {
    $ipaddress = '';
    if (isset($_SERVER['HTTP_CLIENT_IP'])){
        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        $ipaddress = filter_var($ipaddress, FILTER_VALIDATE_IP);
    }
    else if(isset($_SERVER['HTTP_X_FORWARDED_FOR'])){
        $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        $ipaddress = filter_var($ipaddress, FILTER_VALIDATE_IP);
    }
    else if(isset($_SERVER['HTTP_X_FORWARDED'])){
        $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        $ipaddress = filter_var($ipaddress, FILTER_VALIDATE_IP);
    }
    else if(isset($_SERVER['HTTP_FORWARDED_FOR'])){
        $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        $ipaddress = filter_var($ipaddress, FILTER_VALIDATE_IP);
    }
    else if(isset($_SERVER['HTTP_FORWARDED'])){
        $ipaddress = $_SERVER['HTTP_FORWARDED'];
        $ipaddress = filter_var($ipaddress, FILTER_VALIDATE_IP);
    }
    else if(isset($_SERVER['REMOTE_ADDR'])){
        $ipaddress = $_SERVER['REMOTE_ADDR'];
        $ipaddress = filter_var($ipaddress, FILTER_VALIDATE_IP);
    }
    else
        $ipaddress = 'UNKNOWN';
    return $ipaddress;
}

?>