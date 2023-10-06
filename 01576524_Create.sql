

create table onlineshop(
shopid			integer not null,
Shopname	    varchar2(50),
website			varchar2(100),
constraint pk_onlineshop primary key (shopid)
);


create table category(
categoryid		integer not null,
categoryname    varchar2(50),
target_market   varchar2(50),
constraint pk_category primary key (categoryid)
);


create table onlineshop_category(
shopid			integer not null,
categoryid		integer not null,
constraint pk_onlineshop_category primary key (shopid, categoryid),
constraint fk_onlineshop_category_onlineshop foreign key (shopid) references onlineshop (shopid),
constraint fk_onlineshop_category_category foreign key (categoryid) references category_ (categoryid)
);


create table product(
productid		integer not null,
categoryid		integer not null,
productname		varchar2(100) not null,
quantity		decimal(8,2) not null,
price			decimal(8,2) not null,
constraint pk_product primary key (productid),
constraint fk_product_category foreign key (categoryid) references category_ (categoryid),
constraint ch_quantity check (quantity >1),
constraint ch_price check (price >1)

);


create table customer(
customerid      integer not null,
customername    varchar2(50),
date_of_birth   date ,
email   		varchar2(50),
address 		varchar2(250),
influencerid 	integer,
constraint pk_customer primary key (customerid),
constraint fk_customer foreign key (influencerid) references customer (customerid)
);


create table orders(
orderid			integer not null,
customerid		integer not null,
orderdate		date not null,
address			varchar2(250),
ordersize		decimal(8,2),
ordercost		decimal(8,2),
constraint pk_orders primary key (orderid),
constraint fk_orders_customer foreign key (customerid) references customer (customerid)
);


create table cart(
cartid			integer not null,
orderid			integer not null,
productid		integer not null,
quantity		decimal(8,2),
price			decimal(8,2),
constraint pk_cart primary key (cartid),
constraint fk_cart_orders foreign key (orderid) references orders (orderid),
constraint fk_cart_prodcut foreign key (productid) references product (productid),
constraint ch_quantity_cart CHECK (quantity > 0) ,
constraint ch_price_cart CHECK (price > 0)

);



create table shipment (
shipmentid		integer not null,
orderid			integer not null,
deliver_time	date,
deliver_by		varchar2(50),
warehouse		varchar2(50),
shipmentsize	decimal(8,2),
shipmentcost	decimal(8,2),
constraint pk_shipment primary key (shipmentid),
constraint fk_shipment_orders foreign key (orderid) references orders(orderid)
);


                              -------Milestone 3-------
                              
--Creates View of all sales for each customer
 
Create or replace view  cutomersales as
select customer.customerid, customer.customername, sum(cart.price*cart.quantity) sales
from customer join orders on customer.customerid = orders.customerid
join cart on orders.orderid = cart.orderid
group by customer.customerid, customer.customername;


--returns Total Order Costs (SUM)

CREATE VIEW TotalOrderCost AS
Select SUM (ordercost)AS Cost
FROM Orders   ;

--return Customer Order_date

Create or replace view customer_order_date as
select customer.customerid, customername, max(orders.orderdate) last_order
from orders join customer on orders.customerid = customer.customerid
group by customer.customerid, customername;


--Auto increment for product table

CREATE SEQUENCE seq_ProductID
MINVALUE 1
START WITH 1
INCREMENT BY 1;



CREATE OR REPLACE TRIGGER trig_product
    BEFORE INSERT ON Product
    FOR EACH ROW
 BEGIN 
    SELECT seq_Product.NEXTVAL
    INTO :new.ProductID
    FROM DUAL;
    END;
    /


