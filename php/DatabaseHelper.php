<?php

class DatabaseHelper
{
    // Since the connection details are constant, define them as const
    // We can refer to constants like e.g. DatabaseHelper::username
    
	const username = 'a01576524'; // use a + your matriculation number
    const password = 'dbs20'; // use your oracle db password
	const con_string = 'oracle-lab.cs.univie.ac.at:1521/lab';  //on almighty "lab" is sufficient

    // Since we need only one connection object, it can be stored in a member variable.
    // $conn is set in the constructor.
    protected $conn;
    public $shopname;

    // Create connection in the constructor
    public function __construct()
    {
        try {
            // Create connection with the command oci_connect(String(username), String(password), String(connection_string))
            // The @ sign avoids the output of warnings
            // It could be helpful to use the function without the @ symbol during developing process
            $this->conn = @oci_connect(
                DatabaseHelper::username,
                DatabaseHelper::password,
                DatabaseHelper::con_string
            );

            //check if the connection object is != null
            if (!$this->conn) {
                // die(String(message)): stop PHP script and output message:
                die("DB error: Connection can't be established!");
            }
            
            $this->shopname = $this->getShopName(); 

        } catch (Exception $e) {
            die("DB error: {$e->getMessage()}");
        }
    }

    // Used to clean up
    public function __destruct()
    {
        // clean up
        @oci_close($this->conn);
    }

    // function to Set public shopname 
    private function getShopName(){
        $sql = "select shopname from onlineshop fetch next 1 rows only";
        $statement = @oci_parse($this->conn, $sql);
        @oci_execute($statement);
        @oci_fetch_all($statement, $res, 0, 0, OCI_FETCHSTATEMENT_BY_ROW);
        @oci_free_statement($statement);
        return $res[0]["SHOPNAME"];
    }

    // function to return 2-D array of query result set arranged by rows 
    public function query($sql){
        $statement = @oci_parse($this->conn, $sql);
        @oci_execute($statement);
        @oci_fetch_all($statement, $res, 0, 0, OCI_FETCHSTATEMENT_BY_ROW);
        @oci_free_statement($statement);
        return $res;  
    }

    public function delete($table,$pkid){
        $sql = "delete from $table where " . $this->getPK($table) . " = $pkid";
        $statement = @oci_parse($this->conn, $sql);
        $ex = @oci_execute($statement);
        if($ex){
            @oci_commit($this->conn);
            return "OK";
        } else {
            return @oci_error($statement)['message'];
        } 
        @oci_free_statement($statement);        
    }

    public function update($sql){
        $statement = @oci_parse($this->conn, $sql);
        $ex = @oci_execute($statement);
        if($ex){
            @oci_commit($this->conn);
            return "OK";
        } else {
            return @oci_error($statement)['message'];
        } 
        @oci_free_statement($statement);        
    }


    public function getPK($table){
        $pks = array("customer" => "customerid", 
                     "category" => "categoryid",
                     "product" => "productid",
                     "orders" => "orderid",
                     "cart" => "cartid",
                     "shipment" => "shipmentid");
        return($pks[$table]);
    }


    public function getCustomerList($toFind=""){
        $sql = "select * from customer";
        if ($toFind!="") $sql .= " where upper(customername) like '%" . strtoupper($toFind) . "%'";
        $sql .= ' order by 1';
        $statement = @oci_parse($this->conn, $sql);
        @oci_execute($statement);
        @oci_fetch_all($statement, $res, 0, 0, OCI_FETCHSTATEMENT_BY_ROW);
        @oci_free_statement($statement);
        return $res;
    }

    public function getCategoryList($toFind=""){
        $sql = "select * from category";
        if ($toFind!="") $sql .= " where upper(categoryname) like '%" . strtoupper($toFind) . "%'";
        $sql .= ' order by 1';
        $statement = @oci_parse($this->conn, $sql);
        @oci_execute($statement);
        @oci_fetch_all($statement, $res, 0, 0, OCI_FETCHSTATEMENT_BY_ROW);
        @oci_free_statement($statement);
        return $res;
    }

    public function getProductList($toFind=""){
        $sql = "select product.productid, product.productname, category.categoryname category, product.price, product.quantity 
        from product left join category on product.categoryid = category.categoryid";
        if ($toFind!="") $sql .= " where upper(product.productname) like '%" . strtoupper($toFind) . "%'";
        $sql .= ' order by 1';
        $statement = @oci_parse($this->conn, $sql);
        @oci_execute($statement);
        @oci_fetch_all($statement, $res, 0, 0, OCI_FETCHSTATEMENT_BY_ROW);
        @oci_free_statement($statement);
        return $res;
    }

    public function getOrdersList($toFind=""){
        $sql = "select orders.orderid, orders.orderdate, orders.customerid, customer.customername, orders.ordersize, orders.ordercost
        from orders left join customer on orders.customerid = customer.customerid";

        if ($toFind!="") {
            if (is_numeric($toFind)){
                $sql .= " where orderid = $toFind ";
            } else {
                $sql .= " where upper(customer.customername) like '%" . strtoupper($toFind) . "%'";
            }
        }
        
        $sql .= ' order by 1';
        $statement = @oci_parse($this->conn, $sql);
        @oci_execute($statement);
        @oci_fetch_all($statement, $res, 0, 0, OCI_FETCHSTATEMENT_BY_ROW);
        @oci_free_statement($statement);
        return $res;
    }

    public function getCartList($toFind=""){
        $sql = "select cart.cartid, cart.orderid, substr(product.productname,1,50) productname, cart.price, cart.quantity, cart.quantity * cart.price amount 
        from cart left join product on cart.productid = product.productid";
        if ($toFind!="") $sql .= " where orderid=$toFind";
        $sql .= ' order by 1';
        $statement = @oci_parse($this->conn, $sql);
        @oci_execute($statement);
        @oci_fetch_all($statement, $res, 0, 0, OCI_FETCHSTATEMENT_BY_ROW);
        @oci_free_statement($statement);
        return $res;
    }

    public function getShipmentList($toFind=""){
        $sql = "select * from shipment";
        if ($toFind!="") $sql .= " where shipmentid='$toFind' or orderid='$toFind' ";
        $sql .= ' order by 1';
        $statement = @oci_parse($this->conn, $sql);
        @oci_execute($statement);
        @oci_fetch_all($statement, $res, 0, 0, OCI_FETCHSTATEMENT_BY_ROW);
        @oci_free_statement($statement);
        return $res;
    }

    // This function creates and executes a SQL select statement and returns an array as the result
    // 2-dimensional array: the result array contains nested arrays (each contains the data of a single row)
    public function selectFromCustomerWhere($toFind){
        // Define the sql statement string
        // Notice that the parameters $person_id, $surname, $name in the 'WHERE' clause
        $sql = "SELECT * FROM customer
            WHERE upper(productname) LIKE upper('%{$toFind}%')
            ORDER BY customerid ASC ";
        echo $sql;

        // oci_parse(...) prepares the Oracle statement for execution
        // notice the reference to the class variable $this->conn (set in the constructor)
        $statement = @oci_parse($this->conn, $sql);

        // Executes the statement
        @oci_execute($statement);

        // Fetches multiple rows from a query into a two-dimensional array
        // Parameters of oci_fetch_all:
        //   $statement: must be executed before
        //   $res: will hold the result after the execution of oci_fetch_all
        //   $skip: it's null because we don't need to skip rows
        //   $maxrows: it's null because we want to fetch all rows
        //   $flag: defines how the result is structured: 'by rows' or 'by columns'
        //      OCI_FETCHSTATEMENT_BY_ROW (The outer array will contain one sub-array per query row)
        //      OCI_FETCHSTATEMENT_BY_COLUMN (The outer array will contain one sub-array per query column. This is the default.)
        @oci_fetch_all($statement, $res, 0, 0, OCI_FETCHSTATEMENT_BY_ROW);

        //clean up;
        @oci_free_statement($statement);

        return $res;
    }
	

    // This function creates and executes a SQL insert statement and returns true or false
    public function insertIntoPerson($name, $surname)
    {
        $sql = "INSERT INTO PERSON (NAME, SURNAME) VALUES ('{$name}', '{$surname}')";

        $statement = @oci_parse($this->conn, $sql);
        $success = @oci_execute($statement) && @oci_commit($this->conn);
        @oci_free_statement($statement);
        return $success;
    }

    // Using a Procedure
    // This function uses a SQL procedure to delete a person and returns an errorcode (&errorcode == 1 : OK)
    public function deletePerson($person_id)
    {
        // It is not necessary to assign the output variable,
        // but to be sure that the $errorcode differs after the execution of our procedure we do it anyway
        $errorcode = 0;

        // In our case the procedure P_DELETE_PERSON takes two parameters:
        //  1. person_id (IN parameter)
        //  2. error_code (OUT parameter)

        // The SQL string
        $sql = 'BEGIN P_DELETE_PERSON(:person_id, :errorcode); END;';
        $statement = @oci_parse($this->conn, $sql);

        //  Bind the parameters
        @oci_bind_by_name($statement, ':person_id', $person_id);
        @oci_bind_by_name($statement, ':errorcode', $errorcode);

        // Execute Statement
        @oci_execute($statement);

        //Note: Since we execute COMMIT in our procedure, we don't need to commit it here.
        //@oci_commit($statement); //not necessary

        //Clean Up
        @oci_free_statement($statement);

        //$errorcode == 1 => success
        //$errorcode != 1 => Oracle SQL related errorcode;
        return $errorcode;
    }

    // NOT IN USE - ALTERNATIVE to a simple insert (method return person_id)
    // using a Procedure to add a Person -> the Id of the currently added Person is return (otherwise false)
    public function addPerson($name, $surname)
    {
        $person_id = -1;
        $sql = 'BEGIN P_ADD_PERSON(:name, :surname, :person_id); END;';
        $statement = @oci_parse($this->conn, $sql);

        @oci_bind_by_name($statement, ':name', $name);
        @oci_bind_by_name($statement, ':surname', $surname);
        @oci_bind_by_name($statement, ':person_id', $person_id);


        if (!@oci_execute($statement)) {
            @oci_commit($this->conn);
        }
        @oci_free_statement($statement);

        return $person_id;
    }
}