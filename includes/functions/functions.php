<?php
/* 1
** Title Function v1.0
** Title Function That Echo The Page Title In Case The Page
** Has The Variable $pageTitle And Echo Defult Title For Other Pages
*/

function getTitle() {
    global $pageTitle;
    if ( isset( $pageTitle ) ) {
        echo $pageTitle;
    } else {
        echo 'Default';
    }
}

/* 2
** Home Redirect Function v2.0
** This Function Accept Parameters
** $theMsg = Echo The Message [ Error | Success | Warning ]
** $url = The Link You Want To Redirect To
** $seconds = Seconds Before Redirecting
*/

function redirectHome( $theMsg, $url = null, $seconds = 3 ) {

    if ( $url === null ) {
        $url = 'index.php';
        $link = 'Homepage';
    } else {
        if ( isset( $_SERVER['HTTP_REFERER'] ) && $_SERVER['HTTP_REFERER'] !== '' ) {
            $url = $_SERVER['HTTP_REFERER'];
            $link = 'Previous Page';
        } else {
            $url = 'index.php';
            $link = 'Homepage';
        }
    }

    echo $theMsg;
    echo "<div class='alert alert-info'>You Will Be Redirected to $link After $seconds Seconds.</div>";
    header( "refresh:$seconds;url=$url" );
    exit();
}

/* 3
** Check Items Function v1.0
** Function to Check Item In Database [ Function Accept Parameters ]
** $select = The Item To Select [ Example: user, item, category ]
** $from = The Table To Select From [ Example: users, items, categories ]
** $value = The Value Of Select [ Example: Osama, Box, Electronics ]
*/

function checkItem( $select, $from, $value ) {
    global $con;
    $statement = $con->prepare( "SELECT $select FROM $from WHERE $select = ?" );
    $statement->execute( [$value] );
    $count = $statement->rowCount();
    return $count;
}

/* 4
** Count Number Of Items Function v1.0
** Function To Count Number Of Items Rows
** $item = The Item To Count
** $table = The Table To Choose From
*/

function countItems( $item, $table ) {
    global $con;
    $stmt2 = $con->prepare( "SELECT COUNT($item) FROM $table" );
    $stmt2->execute();
    return $stmt2->fetchColumn();
}

/* 5
** Get Latest Records Function v1.0
** Function To Get Latest Items From Database [ Users, Items, Comments ]
** $select = Field To Select
** $table = The Table To Choose From
** $order = The Desc Ordering
** $limit = Number Of Records To Get
*/

function getLatest( $select, $table, $order, $limit = 5 ) {
    global $con;
    $getStmt = $con->prepare( "SELECT $select FROM $table ORDER BY $order DESC LIMIT $limit" );
    $getStmt->execute();
    $rows = $getStmt->fetchAll();
    return $rows;
}

/* 6
** Get All Function v2.0
** Function To Get All Records From Any Database Table
*/

function getAllFrom( $field, $table, $where = NULL, $and = NULL, $orderField, $ordering = 'DESC' ) {
    global $con;
    $getAll = $con->prepare( "SELECT $field FROM $table $where $and ORDER BY $orderField $ordering" );
    $getAll->execute();
    $all = $getAll->fetchAll();
    return $all;

}

/* 7
** Get Records Function v1.0
** Function To Get Categories From Any Database
*/

function getCat( $field, $table, $orderField, $ordering = 'ASC', $limit = 5 ) {
    global $con;
    $getAll = $con->prepare( "SELECT $field FROM $table ORDER BY $orderField $ordering LIMIT $limit" );
    $getAll->execute();
    $all = $getAll->fetchAll();
    return $all;
}

/* 8
** Get Items Function v1.0
** Function To Get Categories From Any Database
*/

function getItems( $value ) {
    global $con;
    $getItems = $con->prepare( 'SELECT * FROM items WHERE CatId = ?  ORDER BY ItemId DESC' );
    $getItems->execute( [$value] );
    $items = $getItems->fetchAll();
    return $items;
}

function getItems2( $where, $value, $approve = null ) {
    global $con;
   
    if($approve == null){
        $sql='AND Approve = 1';
    }else{
        $sql=null;
    }
    $getItems = $con->prepare( "SELECT * FROM items WHERE $where = ? $sql ORDER BY ItemId DESC" );
    $getItems->execute( [$value] );
    $items = $getItems->fetchAll();
    return $items;
}

/* 9
** Check If User Is Not Activated
** Function To Check The RegStatus Of The User
*/

function checkUserStatus( $user ) {
    global $con;
    $stmtx = $con->prepare( "SELECT 
									UserName, RegStatus 
								FROM 
									users 
								WHERE 
									UserName = ? 
								AND 
									RegStatus = 0" );

    $stmtx->execute( array( $user ) );
    $status = $stmtx->rowCount();
    return $status;

}

?>