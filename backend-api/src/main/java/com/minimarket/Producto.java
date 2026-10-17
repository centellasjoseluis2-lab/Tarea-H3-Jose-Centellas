package com.minimarket;
import io.quarkus.hibernate.orm.panache.PanacheEntityBase;
import jakarta.persistence.Column;
import jakarta.persistence.Entity;
import jakarta.persistence.GeneratedValue;
import jakarta.persistence.GenerationType;
import jakarta.persistence.Id;
import jakarta.persistence.Table;
import java.math.BigDecimal;
@Entity
@Table(name = "Producto")

public class Producto extends PanacheEntityBase {
    @Id
    @GeneratedValue(strategy = GenerationType.IDENTITY)
    public Long id;
    @Column(name = "nombre", length = 100, nullable = false)
    public String nombre;
    @Column(name = "proveedor", length = 100, nullable = false)
    public String proveedor;
    @Column(name = "fecha_vencimiento", nullable = false)
    public String fecha_vencimiento;
    @Column(name = "precio", precision = 10, scale = 2, nullable = false)
    public BigDecimal precio;
    @Column(name = "stock", nullable = false)
    public Integer stock;
    @Column(name = "eliminado", nullable = false)
    public Integer eliminado = 0;
    
}
