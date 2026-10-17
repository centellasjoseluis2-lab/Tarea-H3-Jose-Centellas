package com.minimarket;

import jakarta.transaction.Transactional;
import jakarta.ws.rs.*;
import jakarta.ws.rs.core.MediaType;
import jakarta.ws.rs.core.Response;
import java.math.BigDecimal;
import java.util.List;
import java.util.Map;

@Path("/Producto")
@Produces(MediaType.APPLICATION_JSON)
@Consumes(MediaType.APPLICATION_JSON)
public class ProductoResource {

    private String validar(Producto p) {
        if (p == null) return "Datos del producto requeridos.";
        if (p.nombre == null || p.nombre.trim().isEmpty()) return "El nombre es obligatorio.";
        if (p.proveedor == null || p.proveedor.trim().isEmpty()) return "El proveedor es obligatorio.";
        if (p.fecha_vencimiento == null) return "La fecha de vencimiento es obligatoria.";
        if (p.precio == null || p.precio.compareTo(BigDecimal.ZERO) < 0) return "El precio debe ser mayor o igual a cero.";
        if (p.stock == null || p.stock < 0) return "El stock debe ser mayor o igual a cero.";
        return null;
    }

    // Listar todos los productos activos (eliminado = 0)
    @GET
    public List<Producto> listarActivos() {
        return Producto.list("eliminado = 0 ORDER BY id ASC");
    }

    // Obtener un producto por ID
    @GET
    @Path("/{id}")
    public Response obtenerPorId(@PathParam("id") Integer id) {
        Producto p = Producto.find("id = ?1 AND eliminado = 0", id).firstResult();
        if (p == null) {
            return Response.status(Response.Status.NOT_FOUND)
                    .entity(Map.of("error", "Producto no encontrado")).build();
        }
        return Response.ok(p).build();
    }

    // Registrar un producto nuevo
    @POST
    @Transactional
    public Response crear(Producto nuevo) {
        String error = validar(nuevo);
        if (error != null) {
            return Response.status(Response.Status.BAD_REQUEST)
                    .entity(Map.of("error", error)).build();
        }
        nuevo.id = null;
        nuevo.eliminado = 0;
        nuevo.persist();
        return Response.status(Response.Status.CREATED)
                .entity(Map.of("mensaje", "Producto registrado exitosamente", "id", nuevo.id)).build();
    }

    // Editar un producto existente
    @PUT
    @Path("/{id}")
    @Transactional
    public Response editar(@PathParam("id") Integer id, Producto datos) {
        String error = validar(datos);
        if (error != null) {
            return Response.status(Response.Status.BAD_REQUEST)
                    .entity(Map.of("error", error)).build();
        }
        Producto existente = Producto.find("id = ?1 AND eliminado = 0", id).firstResult();
        if (existente == null) {
            return Response.status(Response.Status.NOT_FOUND)
                    .entity(Map.of("error", "Producto no encontrado")).build();
        }
        existente.nombre = datos.nombre.trim();
        existente.proveedor = datos.proveedor.trim();
        existente.fecha_vencimiento = datos.fecha_vencimiento;
        existente.precio = datos.precio;
        existente.stock = datos.stock;
        return Response.ok(Map.of("mensaje", "Producto actualizado correctamente")).build();
    }

    // Eliminar lógicamente (cambiar estado eliminado a 1)
    @DELETE
    @Path("/{id}")
    @Transactional
    public Response eliminarLogicamente(@PathParam("id") Integer id) {
        Producto existente = Producto.findById(id);
        if (existente == null) {
            return Response.status(Response.Status.NOT_FOUND)
                    .entity(Map.of("error", "Producto no encontrado")).build();
        }
        existente.eliminado = 1;
        return Response.ok(Map.of("mensaje", "Producto eliminado logicamente")).build();
    }
}