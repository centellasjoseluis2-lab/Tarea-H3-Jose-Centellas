CREATE DATABASE IF NOT EXISTS db_minimarket_pro413;
USE db_minimarket_pro413;

CREATE TABLE IF NOT EXISTS Producto (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    proveedor VARCHAR(100) NOT NULL,
    fecha_vencimiento DATE NOT NULL,
    precio DECIMAL(10,2) NOT NULL CHECK (precio >= 0),
    stock INT NOT NULL CHECK (stock >= 0),
    eliminado TINYINT(1) NOT NULL DEFAULT 0
);

INSERT INTO Producto (nombre, proveedor, fecha_vencimiento, precio, stock, eliminado) VALUES
('Coca Cola 2L', 'Embol S.A.', '2027-05-15', 13.00, 45, 0),
('Fanta Naranja 2L', 'Embol S.A.', '2027-05-20', 12.50, 30, 0),
('Sprite 2L', 'Embol S.A.', '2027-06-10', 12.50, 25, 0),
('Agua Vital sin Gas 2L', 'Embol S.A.', '2027-12-01', 6.00, 60, 0),
('Leche Pil Entera 1L', 'PIL Andina', '2026-11-30', 6.80, 50, 0),
('Yogurt Pil Frutilla 1L', 'PIL Andina', '2026-11-15', 11.50, 20, 0),
('Mantequilla Regia 200g', 'Industrias Venado', '2027-02-28', 14.00, 15, 0),
('Queso Mozzarella 500g', 'PIL Andina', '2026-12-10', 28.00, 10, 0),
('Arroz Grano de Oro 1Kg', 'Grano de Oro', '2028-01-20', 9.50, 80, 0),
('Azucar Guabira 1Kg', 'Ingenio Guabira', '2028-06-15', 7.00, 90, 0),
('Aceite Fino 900ml', 'Industrias de Aceite Fino', '2027-09-10', 13.50, 40, 0),
('Fideo Lazaro Spaguetti 400g', 'Molino Andino', '2027-11-05', 5.50, 70, 0),
('Atun Van Camps en Aceite', 'Distribuidora Minoil', '2029-03-12', 14.50, 35, 0),
('Galletas Mabel Maria', 'Nestle Bolivia', '2027-04-18', 4.50, 55, 0),
('Papas Pringles Original 124g', 'Distribuidora Minoil', '2027-08-22', 22.00, 18, 0),
('Chocolate Sublime Clasico', 'Nestle Bolivia', '2027-03-30', 3.50, 100, 0),
('Cafe Nescafe Tradicion 50g', 'Nestle Bolivia', '2028-05-01', 16.00, 25, 0),
('Detergente Omo 800g', 'Unilever Andina', '2029-01-01', 19.50, 30, 0),
('Papel Higienico Scott 4 rollos', 'Kimberly-Clark', '2030-01-01', 12.00, 40, 0),
('Jabon Liquido Protex 250ml', 'Colgate-Palmolive', '2028-10-15', 18.00, 22, 0);