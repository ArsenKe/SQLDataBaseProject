//Database Systems (Module IDS) 

import java.sql.*;
import java.util.ArrayList;

// The DatabaseHelper class encapsulates the communication with our database
class DatabaseHelper {
    // Database connection info
   private static final String DB_CONNECTION_URL = "jdbc:oracle:thin:@oracle-lab.cs.univie.ac.at:1521:lab";
   private static final String USER = "a01576524"; //TODO: use a + matriculation number
   private static final String PASS = "dbs20"; //TODO: use your password (default: dbs19)


    // The name of the class loaded from the ojdbc14.jar driver file
    //private static final String CLASSNAME = "oracle.jdbc.driver.OracleDriver";

    // We need only one Connection and one Statement during the execution => class variable
    private static Statement stmt;
    private static Connection con;

    private String sql;

    //CREATE CONNECTION
    DatabaseHelper() {
        try {
            // establish connection to database
            con = DriverManager.getConnection(DB_CONNECTION_URL, USER, PASS);
            stmt = con.createStatement();

        } catch (Exception e) {
            e.printStackTrace();
        }
    }



    //DROP ALL
    void dropAll(){
        ArrayList<String> tables = new ArrayList<String>();
        tables.add("shipment");
        tables.add("cart");
        tables.add("orders");
        tables.add("product");
        tables.add("customer");
        tables.add("onlineshop_category");
        tables.add("category");
        tables.add("onlineshop");

        for (String table : tables){
            try {
                sql = "drop table " + table + " cascade constraints";
                stmt.execute(sql);
            } catch (Exception e){ }
        }
    }

    void createTables(){
        try {
            sql = "create table onlineshop("
                    + " shopid			integer not null,"
                    + " Shopname	    varchar2(50),"
                    + " website			varchar2(100),"
                    + " constraint pk_onlineshop primary key (shopid)"
                    + " )";
            stmt.execute(sql);

            sql = "create table category("
                    + " categoryid		integer not null,"
                    + " categoryname    varchar2(50),"
                    + " target_market   varchar2(50),"
                    + " constraint pk_category primary key (categoryid)"
                    + " )";
            stmt.execute(sql);

            sql = "create table onlineshop_category("
                    + " shopid			integer not null,"
                    + " categoryid		integer not null,"
                    + " constraint pk_onlineshop_category primary key (shopid, categoryid)"
                    + " )";
            stmt.execute(sql);

            sql = "create table product("
                    + " productid		integer not null,"
                    + " categoryid		integer not null,"
                    + " productname		varchar2(100) not null,"
                    + " quantity		decimal(8,2) not null,"
                    + " price			decimal(8,2) not null,"
                    + " constraint pk_product primary key (productid),"
                    + " constraint ch_quantity check (quantity >1),"
                    + " constraint ch_price check (price >1)"
                    + " )";
            stmt.execute(sql);

            sql = "create table customer("
                    + " customerid      integer not null,"
                    + " customername    varchar2(50),"
                    + " date_of_birth   date ,"
                    + " email   		varchar2(50),"
                    + " address 		varchar2(250),"
                    + " influencerid 	integer,"
                    + " constraint pk_customer primary key (customerid)"
                    + " )";
            stmt.execute(sql);

            sql = "create table orders("
                    + " orderid			integer not null,"
                    + " customerid		integer not null,"
                    + " orderdate		date not null,"
                    + " address			varchar2(250),"
                    + " ordersize		decimal(8,2),"
                    + " ordercost		decimal(8,2),"
                    + " constraint pk_orders primary key (orderid)"
                    + " )";
            stmt.execute(sql);

            sql = "create table cart("
                    + " cartid			integer not null,"
                    + " orderid			integer not null,"
                    + " productid		integer not null,"
                    + " quantity		decimal(8,2),"
                    + " price			decimal(8,2),"
                    + " constraint pk_cart primary key (cartid),"
                    + " constraint ch_quantity_cart CHECK (quantity > 0) ,"
                    + " constraint ch_price_cart CHECK (price > 0)"
                    + " )";
            stmt.execute(sql);

            sql = "create table shipment ("
                    + " shipmentid		integer not null,"
                    + " orderid			integer not null,"
                    + " deliver_time	date,"
                    + " deliver_by		varchar2(50),"
                    + " warehouse		varchar2(50),"
                    + " shipmentsize	decimal(8,2),"
                    + " shipmentcost	decimal(8,2),"
                    + " constraint pk_shipment primary key (shipmentid)"
                    + " )";
            stmt.execute(sql);

        } catch (Exception e){
            System.out.println(sql);
            System.err.println("Error at: createTables\nmessage: " + e.getMessage());
        }
    }

    void createForeignKeys(){
        try {
            sql = "alter table onlineshop_category add constraint fk_onlineshop_categoryonlineshop foreign key (shopid) references onlineshop (shopid)";
            stmt.execute(sql);
            sql = "alter table onlineshop_category add constraint fk_onlineshop_categorycategory foreign key (categoryid) references category (categoryid)";
            stmt.execute(sql);
            sql = "alter table product add constraint fk_product_category foreign key (categoryid) references category (categoryid)";
            stmt.execute(sql);
            sql = "alter table customer add constraint fk_customer foreign key (influencerid) references customer (customerid)";
            stmt.execute(sql);
            sql = "alter table orders add constraint fk_orders_customer foreign key (customerid) references customer (customerid)";
            stmt.execute(sql);
            sql = "alter table cart add constraint fk_cart_orders foreign key (orderid) references orders (orderid)";
            stmt.execute(sql);
            sql = "alter table cart add constraint fk_cart_prodcut foreign key (productid) references product (productid)";
            stmt.execute(sql);
            sql = "alter table shipment add constraint fk_shipment_orders foreign key (orderid) references orders(orderid)";
            stmt.execute(sql);
        } catch (Exception e){
            System.out.println("\n" + sql);
            System.err.println("Error at: createTables\nmessage: " + e.getMessage());
        }
    }

    //INSERT INTO ONLINESHOP
    void insertIntoOnlineShop(Integer ShopId, String ShopName, String Website) {
        try {
            sql = "INSERT INTO OnlineShop (ShopId, ShopName, Website) VALUES (" +
                    ShopId +
                    ",'" +  ShopName + "'" +
                    ",'" +  Website + "'" +
                    ")";
            stmt.execute(sql);

        } catch (Exception e) {
            System.out.println(sql);
            System.err.println("Error at: insertIntoOnlineShop\nmessage: " + e.getMessage());
        }
    }

    //INSERT INTO CATEGORY
    void insertIntoCategory(Integer CategoryId, String CategoryName, String Target_Market) {
        try {
            sql = "INSERT INTO Category (CategoryId, CategoryName, Target_Market) VALUES (" +
                    CategoryId +
                    ",'" +  CategoryName + "'" +
                    ",'" +  Target_Market + "'" +
                    ")";
            stmt.execute(sql);
        } catch (Exception e) {
            System.out.println(sql);
            System.err.println("Error at: insertIntoCategory\nmessage: " + e.getMessage());
        }
    }

    //INSERT INTO ONLINESHOP_CATEGORY
    void insertIntoOnlineShopCategory(Integer Shopid, Integer CategoryId) {
        try {
            sql = "INSERT INTO OnlineShop_Category (ShopId, CategoryId) VALUES (" +
                    Shopid +
                    "," +  CategoryId +
                    ")";
            stmt.execute(sql);
        } catch (Exception e) {
            System.out.println(sql);
            System.err.println("Error at: insertIntoOnlineShopCategory\nmessage: " + e.getMessage());
        }
    }

    //INSERT INTO CUSTOMER
    void insertIntoCustomer(Integer CustomerId, String CustomerName, String Date_of_Birth, String Email, String Address, String InfluencerId) {
        String OptionalInfluencer = InfluencerId;
        try {
            if(OptionalInfluencer.equals("")) OptionalInfluencer="Null";
            String sql = "INSERT INTO Customer (CustomerId, CustomerName, Date_of_Birth, Email, Address, InfluencerId) VALUES (" +
                    CustomerId +
                    ",'" + CustomerName + "'" +
                    ",'" + Date_of_Birth + "'" +
                    ",'" + Email + "'" +
                    ",'" + Address + "'" +
                    "," +  OptionalInfluencer +
                    ")";
            stmt.execute(sql);
        } catch (Exception e) {
            System.err.println("Error at: insertIntoCustomer\nmessage: " + e.getMessage());
        }
    }

    //INSERT INTO PRODUCT
    void insertIntoProduct(Integer ProductId, Integer CategoryId, String ProductName, Float Quantity, Float Price) {
        try {
            sql = "INSERT INTO PRODUCT (ProductId, CategoryId, ProductName, Quantity, Price) VALUES (" +
                    ProductId +
                    "," +  CategoryId +
                    ",'" + ProductName + "'" +
                    "," +  Quantity +
                    "," +  Price +
                    ")";
            stmt.execute(sql);
        } catch (Exception e) {
            System.out.println(sql);
            System.err.println("Error at: insertIntoProduct\nmessage: " + e.getMessage());
        }
    }

    //INSERT INTO ORDERS
    void insertIntoOrders(Integer OrderId, Integer CustomerId, String OrderDate, String Address, Float OrderSize, Float OrderCost) {
        try {
            sql = "INSERT INTO Orders (OrderId, CustomerId, OrderDate, Address, OrderSize, OrderCost) VALUES (" +
                    OrderId +
                    "," + CustomerId +
                    ",'" + OrderDate + "'" +
                    ",'" + Address + "'" +
                    "," + OrderSize +
                    "," + OrderCost +
                    ")";
            stmt.execute(sql);
        } catch (Exception e) {
            System.out.println(sql);
            System.err.println("Error at: insertIntoOrders\nmessage: " + e.getMessage());
        }
    }

    //INSERT INTO CART
    void insertIntoCart(Integer CartId, Integer OrderId, Integer ProductId, Float Quantity, Float Price) {
        try {
            sql = "INSERT INTO Cart (CartId, OrderId, ProductId, Quantity, Price) VALUES (" +
                    CartId +
                    "," + OrderId +
                    "," +  ProductId +
                    "," +  Quantity +
                    "," +  Price +
                    ")";
            stmt.execute(sql);
        } catch (Exception e) {
            System.out.println(sql);
            System.err.println("Error at: insertIntoCart\nmessage: " + e.getMessage());
        }
    }

    //INSERT INTO SHIPMENT
    void insertIntoShipment(Integer ShipmentId, Integer OrderId, String Deliver_Time, String Deliver_By, String Warehouse, Float ShipmentSize, Float ShipmentCost) {
        try {
            sql = "INSERT INTO Shipment (ShipmentId, OrderId, Deliver_Time, Deliver_By, Warehouse, ShipmentSize, ShipmentCost) VALUES (" +
                    ShipmentId +
                    ","  + OrderId +
                    ",'" + Deliver_Time + "'" +
                    ",'" + Deliver_By + "'" +
                    ",'" + Warehouse + "'" +
                    ","  + ShipmentSize +
                    ","  + ShipmentCost +
                    ")";
            stmt.execute(sql);
        } catch (Exception e) {
            System.out.println(sql);
            System.err.println("Error at: insertIntoShipment\nmessage: " + e.getMessage());
        }
    }

    public void close()  {
        try {
            stmt.close(); //clean up
            con.close();
        } catch (Exception ignored) {
        }
    }
}