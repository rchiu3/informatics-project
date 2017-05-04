-- Drop existing tables if they exist so we can create empty tables --
DROP TABLE IF EXISTS OrderLine;
DROP TABLE IF EXISTS Order_T;
DROP TABLE IF EXISTS Customer;
DROP TABLE IF EXISTS Product;
DROP TABLE IF EXISTS Category;
DROP TABLE IF EXISTS Employee;
DROP TABLE IF EXISTS Store;
DROP TABLE IF EXISTS Payment;

-- Customer Table --`   

CREATE TABLE Customer (
	CustomerID int unsigned NOT NULL AUTO_INCREMENT,
	CustomerName varchar(30) NOT NULL,
	CustomerAddress varchar(200) NOT NULL,
	CustomerEmail varchar(60) NOT NULL,
	CustomerPass varchar(130),
	PRIMARY KEY (CustomerID)
);

-- Order Table --

CREATE TABLE Order_T (
    OrderID int unsigned NOT NULL AUTO_INCREMENT,
    StoreID int NOT NULL,
    OrderDate varchar(50),
    Paid BOOLEAN,
    CustomerID int unsigned,
    ConfirmationEmail varchar(100),
    OrderName varchar(100),
    DeliveryDate varchar(30),
    DeliveryAddress varchar(150),
    DeliveryTime varchar(20),
    OrderStatus varchar(50),
    PRIMARY KEY (OrderID),
    FOREIGN KEY (CustomerID) REFERENCES Customer(CustomerID)
);

-- Store Table --

CREATE TABLE Store (
    StoreID int unsigned NOT NULL AUTO_INCREMENT,
    StoreName varchar(50) NOT NULL,
    Picture varchar(128),
    PRIMARY KEY (StoreID)
);
 
-- Category Table --

CREATE TABLE Category (
    CategoryID int unsigned NOT NULL AUTO_INCREMENT,
    CategoryName varchar(30),
    PRIMARY KEY (CategoryID)
);

-- Sample category data --

INSERT INTO Category(CategoryName) VALUES ('Dairy');
INSERT INTO Category(CategoryName) VALUES ('Produce');
INSERT INTO Category(CategoryName) VALUES ('Bakery');
INSERT INTO Category(CategoryName) VALUES ('Frozen Foods');
INSERT INTO Category(CategoryName) VALUES ('Meat');
INSERT INTO Category(CategoryName) VALUES ('Seafood');
INSERT INTO Category(CategoryName) VALUES ('Beverages');
INSERT INTO Category(CategoryName) VALUES ('Grains & Pasta');

-- Product Table --

CREATE TABLE Product (
    ProductID int unsigned NOT NULL AUTO_INCREMENT,
    ProductName varchar(50) NOT NULL,
    Price DECIMAL(5,2) NOT NULL,
    CategoryID int unsigned NOT NULL,
    Inventory INT NOT NULL,
    StoreID int unsigned NOT NULL,
    Picture varchar(128),
    PRIMARY KEY (ProductID),
    FOREIGN KEY (StoreID) REFERENCES Store(StoreID),
    FOREIGN KEY (CategoryID) REFERENCES Category(CategoryID)
);

-- OrderLine Table --

CREATE TABLE OrderLine (
    OrderLineID int unsigned NOT NULL AUTO_INCREMENT,
    Quantity int unsigned NOT NULL,
    OrderID int unsigned NOT NULL,
    ProductID int unsigned NOT NULL, 
    PRIMARY KEY (OrderLineID),
    FOREIGN KEY (OrderID) REFERENCES Order_T(OrderID),
    FOREIGN KEY (ProductID) REFERENCES Product(ProductID)
);

-- Employee Table --

CREATE TABLE Employee (
    EmployeeID int unsigned NOT NULL AUTO_INCREMENT,
    EmployeeName varchar(50) NOT NULL,
    EmployeeAdmin BOOLEAN NOT NULL,
    EmployeeEmail varchar(100) NOT NULL,
    EmployeePass varchar(300) NOT NULL,
    StoreID int unsigned NOT NULL,
    PRIMARY KEY (EmployeeID),
    FOREIGN KEY (StoreID) REFERENCES Store(StoreID)
);