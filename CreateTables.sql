-- Drop existing tables if they exist so we can create empty tables --
DROP TABLE IF EXISTS OrderLine;
DROP TABLE IF EXISTS Order_T;
DROP TABLE IF EXISTS Customer;
DROP TABLE IF EXISTS Product;
DROP TABLE IF EXISTS Category;
DROP TABLE IF EXISTS Employee;
DROP TABLE IF EXISTS Store;

-- Customer Table --

CREATE TABLE Customer (
	CustomerID int unsigned NOT NULL AUTO_INCREMENT,
	CutomerName varchar(30) NOT NULL,
	Address varchar(200) NOT NULL,
	Email varchar(60) NOT NULL,
	Username varchar(30),
	HashedPass varchar(130),
	PRIMARY KEY (CustomerID)
);
	
	-- Should we include payment info in customer table if so what is needed? --
	-- Credit Card Number, Name on Card, Billing Address, CSV, Expiration Date, Card Type --

-- Order Table --

CREATE TABLE Order_T (
    OrderID int unsigned NOT NULL AUTO_INCREMENT,
    OrderDate DATETIME DEFAULT NULL,
    Paid BOOLEAN,
    CustomerID int unsigned NOT NULL,
    DeliveryDate DATE NOT NULL,
    OrderStatus varchar(50),
    PRIMARY KEY (OrderID),
    FOREIGN KEY (CustomerID) REFERENCES Customer(CustomerID)
);

-- Store Table --

CREATE TABLE Store (
    StoreID int unsigned NOT NULL AUTO_INCREMENT,
    StoreName varchar(50) NOT NULL, 
    PRIMARY KEY (StoreID)
);

-- Sample store data --

INSERT INTO Store(StoreName) VALUES ("John's Grocery");
 
-- Category Table --

CREATE TABLE Category (
    CategoryID int unsigned NOT NULL AUTO_INCREMENT,
    CategoryName varchar(30),
    PRIMARY KEY (CategoryID)
);

-- Sample category data --

INSERT INTO Category(CategoryName) VALUES ('Dairy');

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

-- Sample product data --

INSERT INTO Product(ProductName, Price, CategoryID, Inventory, StoreID) VALUES ('2% Milk - 1 Gallon', 4.99, 1, 100, 1);

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