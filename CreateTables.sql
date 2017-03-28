-- Customer Table --
DROP TABLE IF EXISTS Customer;

CREATE TABLE Customer
(
	CustomerID int unsigned NOT NULL AUTO_INCREMENT,
	CutomerName VARCHAR(30) NOT NULL,
	Address VARCHAR (100) NOT NULL,
	Email VARCHAR (60) NOT NULL,
	Username VARCHAR (30),
	HashedPass VARCHAR (130),
	PRIMARY KEY (CustomerID)
	
	-- Should we include payment info in customer table if so what is needed?--
	-- Credit Card Number, Name on Card, Billing Address, CSV, Expiration Date, Card Type --
	
);

-- Order Table --
DROP TABLE IF EXISTS Order_T;

CREATE TABLE Order_T
(
OrderID int unsigned NOT NULL AUTO_INCREMENT,
OrderDate DATETIME NOT NULL,
Paid BOOL NOT NULL DEFAULT '0',
CustomerID NOT NULL, 
DeliveryDate DATETIME NOT NULL,
Status BOOL NOT NULL DEFAULT '0',

PRIMARY KEY (OrderID),
FOREIGN KEY (CustomerID) REFERENCES customer(CustomerID)

);

-- Store Table --
DROP TABLE IF EXISTS Store;

CREATE TABLE Store
(
StoreID int unsigned NOT NULL AUTO_INCREMENT,
StoreName VARCHAR (50) NOT NULL, 

PRIMARY KEY (StoreID)

);

-- Product Table --
DROP TABLE IF EXISTS Product;

CREATE TABLE Product
(
ProductID int unsigned NOT NULL AUTO_INCREMENT,
ProductName VARCHAR (30) NOt NULL,
Price INT NOT NULL,
Category VARCHAR (30) NOT NULL,
Inventory INT NOT NULL,
StoreID NOT NULL, 
PRIMARY KEY (ProductID),
FOREIGN KEY (StoreID) REFERENCES Store(StoreID)

);

-- OrderLine Table --
DROP TABLE IF EXISTS OrderLine;

CREATE TABLE OrderLine
(
OrderLineID int unsigned NOT NULL AUTO_INCREMENT,
Quantity int unsigned NOT NULL,
OrderID NOT NULL,
ProductID NOT NULL, 

PRIMARY KEY (OrderLineID),
FOREIGN KEY (OrderID) REFERENCES Order_T(OrderID),
FOREIGN KEY (ProductID) REFERENCES Product(ProdctID)

);

-- Employee Table --
DROP TABLE IF EXISTS Employee;

CREATE TABLE Employee
(
EmployeeID int unsigned NOT NULL AUTO_INCREMENT,
EmployeeName VARCHAR (50) NOT NULL,
EmployeeType VARCHAR (35) NOT NULL,
EmployeeUser VARCHAR (30) NOT NULL,
EmployeePass VARCHAR(150) NOT NULL,
StoreID NOT NULL,


PRIMARY KEY (OrderID),
FOREIGN KEY (StoreID) REFERENCES Store(StoreID)

);

