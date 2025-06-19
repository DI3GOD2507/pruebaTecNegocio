CREATE DATABASE ClientesPedidosDB;
GO

CREATE LOGIN laravel_user WITH PASSWORD = 'Laravel123!';
GO

USE ClientesPedidosDB;
GO

CREATE USER laravel_user FOR LOGIN laravel_user;
GO

ALTER ROLE db_owner ADD MEMBER laravel_user;
GO


USE ClientesPedidosDB;
GO

-- Tabla: Persons (Información base de una persona)
CREATE TABLE Persons (
    Id INT PRIMARY KEY IDENTITY(1,1),
    FirstName NVARCHAR(100) NOT NULL,
    LastName NVARCHAR(100) NOT NULL,
    DocumentNumber NVARCHAR(50) NOT NULL,
    Email NVARCHAR(150),
    Phone NVARCHAR(50),
    CreatedAt DATETIME2 DEFAULT SYSDATETIME(),
    UpdatedAt DATETIME2 DEFAULT SYSDATETIME()
);
GO

-- Tabla: Customers (Cliente como entidad de negocio)
CREATE TABLE Customers (
    Id INT PRIMARY KEY IDENTITY(1,1),
    PersonId INT NOT NULL,
    Status BIT NOT NULL DEFAULT 1, -- Activo/Inactivo
    CreatedAt DATETIME2 DEFAULT SYSDATETIME(),
    UpdatedAt DATETIME2 DEFAULT SYSDATETIME(),
    CONSTRAINT FK_Customers_Persons FOREIGN KEY (PersonId) REFERENCES Persons(Id)
);
GO

-- Tabla: Orders (Pedidos de clientes)
CREATE TABLE Orders (
    Id INT PRIMARY KEY IDENTITY(1,1),
    CustomerId INT NOT NULL,
    OrderDate DATETIME2 NOT NULL DEFAULT SYSDATETIME(),
    Status NVARCHAR(20) NOT NULL DEFAULT 'pending', -- pending, completed, cancelled
    TotalAmount DECIMAL(10, 2) NOT NULL,
    Notes NVARCHAR(500),
    CreatedAt DATETIME2 DEFAULT SYSDATETIME(),
    UpdatedAt DATETIME2 DEFAULT SYSDATETIME(),
    CONSTRAINT FK_Orders_Customers FOREIGN KEY (CustomerId) REFERENCES Customers(Id)
);
GO


-- Habilita la salida de mensajes para SCOPE_IDENTITY() si es necesario
SET NOCOUNT ON;

-- ***************************************************************
-- Paso 1: Insertar datos en la tabla Persons
-- ***************************************************************
PRINT 'Insertando datos en la tabla Persons...';

DECLARE @PersonId1 INT;
DECLARE @PersonId2 INT;
DECLARE @PersonId3 INT;
DECLARE @PersonId4 INT;

INSERT INTO Persons (FirstName, LastName, DocumentNumber, Email, Phone)
VALUES
    ('Juan', 'Perez Garcia', '123456789', 'juan.perez@example.com', '555-1234'),
    ('Maria', 'Lopez Fernandez', '987654321', 'maria.lopez@example.com', '555-5678'),
    ('Carlos', 'Rodriguez Sanchez', '112233445', 'carlos.rodriguez@example.com', '555-9012'),
    ('Ana', 'Martinez Torres', '554433221', 'ana.martinez@example.com', '555-3456');

-- Obtener los IDs de las personas insertadas para usarlos como FK
SELECT @PersonId1 = Id FROM Persons WHERE DocumentNumber = '123456789';
SELECT @PersonId2 = Id FROM Persons WHERE DocumentNumber = '987654321';
SELECT @PersonId3 = Id FROM Persons WHERE DocumentNumber = '112233445';
SELECT @PersonId4 = Id FROM Persons WHERE DocumentNumber = '554433221';

PRINT 'Datos en Persons insertados. IDs: ' + CAST(@PersonId1 AS NVARCHAR) + ', ' + CAST(@PersonId2 AS NVARCHAR) + ', ' + CAST(@PersonId3 AS NVARCHAR) + ', ' + CAST(@PersonId4 AS NVARCHAR);

-- ***************************************************************
-- Paso 2: Insertar datos en la tabla Customers
-- ***************************************************************
PRINT 'Insertando datos en la tabla Customers...';

DECLARE @CustomerId1 INT;
DECLARE @CustomerId2 INT;
DECLARE @CustomerId3 INT;

INSERT INTO Customers (PersonId, Status)
VALUES
    (@PersonId1, 1), -- Juan Perez es un cliente activo
    (@PersonId2, 1), -- Maria Lopez es una cliente activa
    (@PersonId3, 0); -- Carlos Rodriguez es un cliente inactivo (quizás antiguo o pausado)

-- Obtener los IDs de los clientes insertados
SELECT @CustomerId1 = Id FROM Customers WHERE PersonId = @PersonId1;
SELECT @CustomerId2 = Id FROM Customers WHERE PersonId = @PersonId2;
SELECT @CustomerId3 = Id FROM Customers WHERE PersonId = @PersonId3;

PRINT 'Datos en Customers insertados. IDs: ' + CAST(@CustomerId1 AS NVARCHAR) + ', ' + CAST(@CustomerId2 AS NVARCHAR) + ', ' + CAST(@CustomerId3 AS NVARCHAR);

-- ***************************************************************
-- Paso 3: Insertar datos en la tabla Orders
-- ***************************************************************
PRINT 'Insertando datos en la tabla Orders...';

INSERT INTO Orders (CustomerId, OrderDate, Status, TotalAmount, Notes)
VALUES
    (@CustomerId1, SYSDATETIME(), 'completed', 150.75, 'Primer pedido de Juan. Artículos de electrónica.'),
    (@CustomerId1, DATEADD(day, -7, SYSDATETIME()), 'pending', 29.99, 'Pedido pendiente de Juan. Artículos de oficina.'),
    (@CustomerId2, DATEADD(month, -1, SYSDATETIME()), 'completed', 500.00, 'Pedido grande de Maria. Ropa y accesorios.'),
    (@CustomerId2, DATEADD(day, -3, SYSDATETIME()), 'cancelled', 85.50, 'Pedido cancelado de Maria. Cambio de planes.'),
    (@CustomerId1, DATEADD(hour, -2, SYSDATETIME()), 'pending', 12.00, 'Pequeño pedido reciente.');

PRINT 'Datos en Orders insertados.';

PRINT 'Script de inserción de datos completado.';

-- Verificar los datos insertados (opcional)
-- SELECT * FROM Persons;
-- SELECT * FROM Customers;
-- SELECT * FROM Orders;