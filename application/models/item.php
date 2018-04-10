<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Item extends CI_Model{
	public function __construct(){
		parent::__construct();
	}
	# Obtener el total de las ventas
	public function venta($fecha, $divisiones, $regiones, $zonas, $tiendas, $productos, $proveedores){
		$whereDivisiones = $whereRegiones = $whereZonas = $whereTiendas = $whereProductos = $whereProveedores = "";

		if($divisiones != null) {
			$divisiones = "'" . str_replace(',', "','", implode(',', $divisiones)) . "'";
			$whereDivisiones = "AND items.[Division Code] in ($divisiones)";
		}
		if($regiones != null) {
			$regiones = "'" . str_replace(',', "','", implode(',', $regiones)) . "'";
			$whereRegiones = "AND txr.[Subgroup Code] IN ($regiones)";
		}
		if($zonas != null) {
			$zonas = "'" . str_replace(',', "','", implode(',', $zonas)) . "'";
			$whereZonas = "AND tiendas.[Subgroup Code] IN ($zonas)";
		}
		if($tiendas != null) {
			$tiendas = "'" . str_replace(',', "','", implode(',', $tiendas)) . "'";
			$whereTiendas = "AND inve.[Store No_] IN ($tiendas)";
		}
		if($productos[0] != '') {
			$xproductos = array();
			foreach($productos as $key => $producto){
				$xproducto = explode('-', $producto);
				array_push($xproductos, $xproducto[0]);
			}
			$productos = $xproductos;
			$productos = "'" . str_replace(',', "','", implode(',', $productos)) . "'";
			$whereProductos = "AND inve.[Item No_] IN ($productos)";
		}
		if($proveedores != null) {
			$proveedores = "'" . str_replace(',', "','", implode(',', $proveedores)) . "'";
			$whereProveedores = "inner join [dbo].[COORPORACION_EL_ASTURIANO\$Vendor] as vendor on items.[Vendor No_] = vendor.[No_]";
			$whereProveedores .= " AND items.[Vendor No_] IN ($proveedores)";
		}
		$sql = "SELECT [filtro].*, case ventaPromedio when 0 then 0 else valorInventarioPesos/ventaPromedio end AS diasDeInventario FROM (SELECT SUM(([inve].[Existencia Piezas] - [inve].[VentasNoReg]) * [inve].[Precio Venta]) AS valorInventarioPesos, SUM([inve].[Venta x Dia Pesos]) AS ventaPromedio FROM [BDREPORTS].[dbo].[CTF\$Inventario] inve INNER JOIN [ASTURIANO].[dbo].[COORPORACION_EL_ASTURIANO\$Item] AS items on inve.[Item No_] = items.[No_] $whereDivisiones $whereProductos $whereProveedores INNER JOIN [ASTURIANO].[dbo].[COORPORACION_EL_ASTURIANO\$Distribution Group Member] AS txr ON inve.[Store No_] = txr.[Distrib_ Loc_ Code] and txr.[Subgroup Code] like 'REGION%' $whereRegiones INNER JOIN [ASTURIANO].[dbo].[COORPORACION_EL_ASTURIANO\$Distribution Group Member] AS tiendas ON inve.[Store No_] = tiendas.[Distrib_ Loc_ Code] AND tiendas.[Subgroup Code] like 'ZONA%' $whereZonas $whereTiendas where Date = '$fecha') AS filtro";
		#echo $sql;
		$query = $this->db->query($sql);
		return $query->row();
	}

	# Obtener la venta por division
	public function divisiones($fecha, $divisiones, $regiones, $zonas, $tiendas, $productos, $proveedores){
		$whereDivisiones = $whereRegiones = $whereZonas = $whereTiendas = $whereProductos = $whereProveedores = "";
		if($divisiones != null) {
			$divisiones = "'" . str_replace(',', "','", implode(',', $divisiones)) . "'";
			$whereDivisiones = "AND items.[Division Code] in ($divisiones)";
		}
		if($regiones != null) {
			$regiones = "'" . str_replace(',', "','", implode(',', $regiones)) . "'";
			$whereRegiones = "AND txr.[Subgroup Code] IN ($regiones)";
		}
		if($zonas != null) {
			$zonas = "'" . str_replace(',', "','", implode(',', $zonas)) . "'";
			$whereZonas = "AND tiendas.[Subgroup Code] IN ($zonas)";
		}
		if($tiendas != null) {
			$tiendas = "'" . str_replace(',', "','", implode(',', $tiendas)) . "'";
			$whereTiendas = "AND inve.[Store No_] IN ($tiendas)";
		}
		if($productos[0] != '') {
			$xproductos = array();
			foreach($productos as $key => $producto){
				$xproducto = explode('-', $producto);
				array_push($xproductos, $xproducto[0]);
			}
			$productos = $xproductos;
			$productos = "'" . str_replace(',', "','", implode(',', $productos)) . "'";
			$whereProductos = "AND inve.[Item No_] IN ($productos)";
		}
		if($proveedores != null) {
			$proveedores = "'" . str_replace(',', "','", implode(',', $proveedores)) . "'";
			$whereProveedores = "inner join [dbo].[COORPORACION_EL_ASTURIANO\$Vendor] as vendor on items.[Vendor No_] = vendor.[No_]";
			$whereProveedores .= " AND items.[Vendor No_] IN ($proveedores)";
		}
		$sql = "SELECT 'division' AS tipo, 'false' AS _open, [Division Code] AS division, '' AS region, '' AS zona, '' AS tienda, '' AS producto, '' AS descripcion, 0 AS PrecioVenta, SUM(existenciaPiezas) AS existenciaPiezas, SUM(existenciaPesos) AS existenciaPesos, SUM(ventaPromedioDiaPiezas) AS ventaPromedioDiaPiezas, SUM(ventaPromedioDiaPesos) AS ventaPromedioDiaPesos FROM (SELECT items.[Division Code], ([Existencia Piezas] - VentasNoReg) AS existenciaPiezas, (([Existencia Piezas] - VentasNoReg) * [Precio Venta]) AS existenciaPesos, [Venta x Dia Piezas] AS ventaPromedioDiaPiezas, [Venta x Dia Pesos] AS ventaPromedioDiaPesos FROM [BDREPORTS].[dbo].[CTF\$Inventario] inve INNER JOIN [ASTURIANO].[dbo].[COORPORACION_EL_ASTURIANO\$Item] AS items on inve.[Item No_] = items.[No_] $whereDivisiones $whereProductos $whereProveedores INNER JOIN [ASTURIANO].[dbo].[COORPORACION_EL_ASTURIANO\$Distribution Group Member] AS txr ON inve.[Store No_] = txr.[Distrib_ Loc_ Code] and txr.[Subgroup Code] like 'REGION%' $whereRegiones INNER JOIN [ASTURIANO].[dbo].[COORPORACION_EL_ASTURIANO\$Distribution Group Member] AS tiendas ON inve.[Store No_] = tiendas.[Distrib_ Loc_ Code] AND tiendas.[Subgroup Code] like 'ZONA%' $whereZonas $whereTiendas where Date = '$fecha') AS divisiones GROUP BY [Division Code] ORDER BY [Division Code] ASC";
		#echo $sql;
		$query = $this->db->query($sql);
		$divisiones = $query->result();
		foreach ($divisiones as $division) {
			$division->diasDeInventario = $division->ventaPromedioDiaPesos == 0 ? 0 : $division->existenciaPesos / $division->ventaPromedioDiaPesos;
		}
		return $divisiones;
	}

	# Obtener la venta por region
	public function regiones($fecha, $division, $regiones, $zonas, $tiendas, $productos, $proveedores){
		$whereRegiones = $whereZonas = $whereTiendas = $whereProductos = "";
		if($regiones != null) {
			$regiones = "'" . str_replace(',', "','", implode(',', $regiones)) . "'";
			$whereRegiones = "AND txr.[Subgroup Code] IN ($regiones)";
		}
		if($zonas != null) {
			$zonas = "'" . str_replace(',', "','", implode(',', $zonas)) . "'";
			$whereZonas = "AND tiendas.[Subgroup Code] IN ($zonas)";
		}
		if($tiendas != null) {
			$tiendas = "'" . str_replace(',', "','", implode(',', $tiendas)) . "'";
			$whereTiendas = "AND inve.[Store No_] IN ($tiendas)";
		}
		if($productos[0] != '') {
			$xproductos = array();
			foreach($productos as $key => $producto){
				$xproducto = explode('-', $producto);
				array_push($xproductos, $xproducto[0]);
			}
			$productos = $xproductos;
			$productos = "'" . str_replace(',', "','", implode(',', $productos)) . "'";
			$whereProductos = "AND inve.[Item No_] IN ($productos)";
		}
		if($proveedores != null) {
			$proveedores = "'" . str_replace(',', "','", implode(',', $proveedores)) . "'";
			$whereProveedores = "inner join [dbo].[COORPORACION_EL_ASTURIANO\$Vendor] as vendor on items.[Vendor No_] = vendor.[No_]";
			$whereProveedores .= " AND items.[Vendor No_] IN ($proveedores)";
		}

		$sql = "SELECT 'region' as tipo, 'false' as _open, '$division' as division, [Subgroup Code] as region, '' as zona, '' as tienda, '' as producto, '' as descripcion, 0 as PrecioVenta, sum(existenciaPiezas) as existenciaPiezas, sum(existenciaPesos) as existenciaPesos, sum(ventaPromedioDiaPiezas) as ventaPromedioDiaPiezas, sum(ventaPromedioDiaPesos) as ventaPromedioDiaPesos FROM (SELECT txr.[Subgroup Code], [Precio Venta] as precioVenta, ([Existencia Piezas] - VentasNoReg) as existenciaPiezas, (([Existencia Piezas] - VentasNoReg) * [Precio Venta]) as existenciaPesos, [Venta x Dia Piezas] as ventaPromedioDiaPiezas, [Venta x Dia Pesos] as ventaPromedioDiaPesos FROM [BDREPORTS].[dbo].[CTF\$Inventario] inve INNER JOIN [ASTURIANO].[dbo].[COORPORACION_EL_ASTURIANO\$Item] AS items on inve.[Item No_] = items.[No_] AND items.[Division Code] = '$division' $whereProductos $whereProveedores INNER JOIN [ASTURIANO].[dbo].[COORPORACION_EL_ASTURIANO\$Distribution Group Member] AS txr ON inve.[Store No_] = txr.[Distrib_ Loc_ Code] and txr.[Subgroup Code] like 'REGION%' $whereRegiones INNER JOIN [ASTURIANO].[dbo].[COORPORACION_EL_ASTURIANO\$Distribution Group Member] AS tiendas ON inve.[Store No_] = tiendas.[Distrib_ Loc_ Code] AND tiendas.[Subgroup Code] like 'ZONA%' $whereZonas $whereTiendas where Date = '$fecha') AS regiones GROUP BY [Subgroup Code] ORDER BY [Subgroup Code] ASC";
		#echo $sql;
		$query = $this->db->query($sql);
		$regiones = $query->result();
		foreach ($regiones as $region) {
			$region->diasDeInventario = $region->ventaPromedioDiaPesos == 0 ? 0 : $region->existenciaPesos / $region->ventaPromedioDiaPesos;
		}
		return $regiones;
	}

	# Obtener la venta por zona
	public function zonas($fecha, $division, $region, $zonas, $tiendas, $productos, $proveedores){
		$whereRegiones = $whereZonas = $whereTiendas = $whereProductos = $whereProveedores = "";
		if($zonas != null) {
			$zonas = "'" . str_replace(',', "','", implode(',', $zonas)) . "'";
			$whereZonas = "AND tiendas.[Subgroup Code] IN ($zonas)";
		}
		if($tiendas != null) {
			$tiendas = "'" . str_replace(',', "','", implode(',', $tiendas)) . "'";
			$whereTiendas = "AND inve.[Store No_] IN ($tiendas)";
		}
		if($productos[0] != '') {
			$xproductos = array();
			foreach($productos as $key => $producto){
				$xproducto = explode('-', $producto);
				array_push($xproductos, $xproducto[0]);
			}
			$productos = $xproductos;
			$productos = "'" . str_replace(',', "','", implode(',', $productos)) . "'";
			$whereProductos = "AND inve.[Item No_] IN ($productos)";
		}
		if($proveedores != null) {
			$proveedores = "'" . str_replace(',', "','", implode(',', $proveedores)) . "'";
			$whereProveedores = "inner join [dbo].[COORPORACION_EL_ASTURIANO\$Vendor] as vendor on items.[Vendor No_] = vendor.[No_]";
			$whereProveedores .= " AND items.[Vendor No_] IN ($proveedores)";
		}

		$sql = "SELECT 'zona' as tipo, 'false' as _open, '$division' as division, '$region' as region, [Subgroup Code] as zona, '' as tienda, '' as producto, '' as descripcion, 0 as PrecioVenta, sum(existenciaPiezas) as existenciaPiezas, sum(existenciaPesos) as existenciaPesos, sum(ventaPromedioDiaPiezas) as ventaPromedioDiaPiezas, sum(ventaPromedioDiaPesos) as ventaPromedioDiaPesos from (select tiendas.[Subgroup Code], [Precio Venta] as precioVenta, ([Existencia Piezas] - VentasNoReg) as existenciaPiezas, (([Existencia Piezas] - VentasNoReg) * [Precio Venta]) as existenciaPesos, [Venta x Dia Piezas] as ventaPromedioDiaPiezas, [Venta x Dia Pesos] as ventaPromedioDiaPesos FROM [BDREPORTS].[dbo].[CTF\$Inventario] inve INNER JOIN [ASTURIANO].[dbo].[COORPORACION_EL_ASTURIANO\$Item] AS items on inve.[Item No_] = items.[No_] AND items.[Division Code] = '$division' $whereProductos $whereProveedores INNER JOIN [ASTURIANO].[dbo].[COORPORACION_EL_ASTURIANO\$Distribution Group Member] AS txr ON inve.[Store No_] = txr.[Distrib_ Loc_ Code] and txr.[Subgroup Code] like 'REGION%' AND txr.[Subgroup Code] = '$region' INNER JOIN [ASTURIANO].[dbo].[COORPORACION_EL_ASTURIANO\$Distribution Group Member] AS tiendas ON inve.[Store No_] = tiendas.[Distrib_ Loc_ Code] AND tiendas.[Subgroup Code] like 'ZONA%' $whereZonas $whereTiendas where Date = '$fecha') as zonas group by [Subgroup Code] order by [Subgroup Code] asc";
		#echo $sql;
		$query = $this->db->query($sql);
		$zonas = $query->result();
		foreach ($zonas as $zona) {
			$zona->diasDeInventario = $zona->ventaPromedioDiaPesos == 0 ? 0 : $zona->existenciaPesos / $zona->ventaPromedioDiaPesos;
		}
		return $zonas;
	}

	# Obtener la venta por tienda
	public function tiendas($fecha, $division, $region, $zona, $tiendas, $productos, $proveedores){
		$whereTiendas = $whereProductos = $whereProveedores = "";
		if($tiendas != null) {
			$tiendas = "'" . str_replace(',', "','", implode(',', $tiendas)) . "'";
			$whereTiendas = "AND inve.[Store No_] IN ($tiendas)";
		}
		if($productos[0] != '') {
			$xproductos = array();
			foreach($productos as $key => $producto){
				$xproducto = explode('-', $producto);
				array_push($xproductos, $xproducto[0]);
			}
			$productos = $xproductos;
			$productos = "'" . str_replace(',', "','", implode(',', $productos)) . "'";
			$whereProductos = "AND inve.[Item No_] IN ($productos)";
		}
		if($proveedores != null) {
			$proveedores = "'" . str_replace(',', "','", implode(',', $proveedores)) . "'";
			$whereProveedores = "inner join [dbo].[COORPORACION_EL_ASTURIANO\$Vendor] as vendor on items.[Vendor No_] = vendor.[No_]";
			$whereProveedores .= " AND items.[Vendor No_] IN ($proveedores)";
		}

		$sql = "SELECT 'tienda' as tipo, 'false' as _open, '$division' as division, '$region' as region, '$zona' as zona, [Distrib_ Loc_ Code] as tienda, '' as producto, '' as descripcion, 0 as PrecioVenta, sum(existenciaPiezas) as existenciaPiezas, sum(existenciaPesos) as existenciaPesos, sum(ventaPromedioDiaPiezas) as ventaPromedioDiaPiezas, sum(ventaPromedioDiaPesos) as ventaPromedioDiaPesos from (select [Distrib_ Loc_ Code], [Precio Venta] as precioVenta, ([Existencia Piezas] - VentasNoReg) as existenciaPiezas, (([Existencia Piezas] - VentasNoReg) * [Precio Venta]) as existenciaPesos, [Venta x Dia Piezas] as ventaPromedioDiaPiezas, [Venta x Dia Pesos] as ventaPromedioDiaPesos FROM [BDREPORTS].[dbo].[CTF\$Inventario] inve INNER JOIN [ASTURIANO].[dbo].[COORPORACION_EL_ASTURIANO\$Item] AS items on inve.[Item No_] = items.[No_] AND items.[Division Code] = '$division' $whereProductos $whereProveedores INNER JOIN [ASTURIANO].[dbo].[COORPORACION_EL_ASTURIANO\$Distribution Group Member] AS tiendas ON inve.[Store No_] = tiendas.[Distrib_ Loc_ Code] and tiendas.[Subgroup Code] = '$zona' $whereTiendas  where Date = '$fecha') as tiendas group by [Distrib_ Loc_ Code] order by [Distrib_ Loc_ Code] asc";
		#echo $sql;
		$query = $this->db->query($sql);
		$tiendas = $query->result();
		foreach ($tiendas as $tienda) {
			$tienda->diasDeInventario = $tienda->ventaPromedioDiaPesos == 0 ? 0 : $tienda->existenciaPesos / $tienda->ventaPromedioDiaPesos;
		}
		return $tiendas;
	}

	# Obtener la venta por producto
	public function productos($fecha, $division, $region, $zona, $tienda, $productos, $proveedores){
		$whereProductos = $whereProveedores = '';
		if($productos[0] != '') {
			$xproductos = array();
			foreach($productos as $key => $producto){
				$xproducto = explode('-', $producto);
				array_push($xproductos, $xproducto[0]);
			}
			$productos = $xproductos;
			$productos = "'" . str_replace(',', "','", implode(',', $productos)) . "'";
			$whereProductos = "AND inve.[Item No_] IN ($productos)";
		}
		if($proveedores != null) {
			$proveedores = "'" . str_replace(',', "','", implode(',', $proveedores)) . "'";
			$whereProveedores = "inner join [dbo].[COORPORACION_EL_ASTURIANO\$Vendor] as vendor on items.[Vendor No_] = vendor.[No_]";
			$whereProveedores .= " AND items.[Vendor No_] IN ($proveedores)";
		}
		$sql = "SELECT 'producto' as tipo, 'false' as _open, '$division' as division, '$region' as region, '$zona' as zona, '$tienda' as tienda, [Item No_] as producto, '' as descripcion, avg(precioVenta) as PrecioVenta, sum(existenciaPiezas) as existenciaPiezas, sum(existenciaPesos) as existenciaPesos, sum(ventaPromedioDiaPiezas) as ventaPromedioDiaPiezas, sum(ventaPromedioDiaPesos) as ventaPromedioDiaPesos from (select [Item No_], [Precio Venta] as precioVenta, ([Existencia Piezas] - VentasNoReg) as existenciaPiezas, (([Existencia Piezas] - VentasNoReg) * [Precio Venta]) as existenciaPesos, [Venta x Dia Piezas] as ventaPromedioDiaPiezas, [Venta x Dia Pesos] as ventaPromedioDiaPesos from [BDREPORTS].[dbo].[CTF\$Inventario] inve INNER JOIN [ASTURIANO].[dbo].[COORPORACION_EL_ASTURIANO\$Item] AS items on inve.[Item No_] = items.[No_] $whereProveedores where Date = '$fecha' and [Division Code] = '$division' and [Store No_] = '$tienda' $whereProductos) as productos group by [Item No_] order by [Item No_] asc ";
		#echo $sql;
		$query = $this->db->query($sql);
		$productos = $query->result();
		foreach ($productos as $producto) {
			$producto->diasDeInventario = $producto->ventaPromedioDiaPesos == 0 ? 0 : $producto->existenciaPesos / $producto->ventaPromedioDiaPesos;
		}
		return $productos;
	}

	# Obtener el nombre de un producto
	public function producto($producto){
		$query = $this->db->query("SELECT TOP(1) Description FROM [dbo].[COORPORACION_EL_ASTURIANO\$Item] WHERE [No_] = '$producto'");
		return $query->row();
	}

	# Obtener el nombre de una tienda
	public function tienda($tienda){
		$query = $this->db->query("SELECT top(1) Name from [dbo].[COORPORACION_EL_ASTURIANO\$Store] where [No_] = '$tienda'");
		return $query->row();
	}

	# Obtener tiendas asignadas a más de una zona
	public function tiendasDobleZona(){
		$query = $this->db->query("SELECT [No_] AS tienda, count([No_]) AS duplicados FROM [ASTURIANO].[dbo].[COORPORACION_EL_ASTURIANO\$Store] AS S LEFT JOIN [ASTURIANO].[dbo].[COORPORACION_EL_ASTURIANO\$Distribution Group Member] AS DGM ON(S.No_ = DGM.[Distrib_ Loc_ Code])  WHERE DGM.[Subgroup Code] like 'ZONA%' GROUP BY No_ HAVING count([No_])>1");
		return $query->result();
	}

	# Obtener tiendas sin zona asignada
	public function tiendasSinZona(){
		$query = $this->db->query("SELECT * from (SELECT [No_] as tienda, [Name] as nombre, (SELECT top 1[Subgroup Code] FROM  [ASTURIANO].[dbo].[COORPORACION_EL_ASTURIANO\$Distribution Group Member] AS DGM WHERE S.No_ = DGM.[Distrib_ Loc_ Code] AND DGM.[Subgroup Code] like 'ZONA%') AS ZONA FROM [ASTURIANO].[dbo].[COORPORACION_EL_ASTURIANO\$Store] AS S WHERE No_ > 'T000') as ii where ZONA is null");
		return $query->result();
	}

	# Obtener las zonas sin regiones asignadas
	public function zonasSinRegion(){
		$query = $this->db->query("SELECT [Subgroup Code] AS zona FROM [ASTURIANO].[dbo].[COORPORACION_EL_ASTURIANO\$Distribution Group Member] WHERE [Subgroup Code] LIKE 'ZONA%' AND [Subgroup Code] NOT IN(SELECT [Subgroup Code] AS zona FROM [ASTURIANO].[dbo].[COORPORACION_EL_ASTURIANO\$Distribution Group Member] WHERE [Subgroup Code] LIKE 'zona%' AND [Distrib_ Loc_ Code] IN (SELECT [Distrib_ Loc_ Code] FROM [ASTURIANO].[dbo].[COORPORACION_EL_ASTURIANO\$Distribution Group Member] WHERE [Subgroup Code] IN (SELECT * FROM (SELECT [Subgroup Code] AS zona FROM [ASTURIANO].[dbo].[COORPORACION_EL_ASTURIANO\$Distribution Group Member] WHERE [Subgroup Code] LIKE 'REGION%' GROUP BY [Subgroup Code]) AS zonas))) GROUP BY [Subgroup Code]");
		return $query->result();
	}

	# Obtener catalogo de divisiones
	public function catDivisiones(){
		$query = $this->db->query("SELECT [Division Code] AS division FROM [ASTURIANO].[dbo].[COORPORACION_EL_ASTURIANO\$Item] GROUP BY [Division Code] ORDER BY [Division Code] ASC");
		return $query->result();
	}

	# Obtener catalogo de regiones
	public function catRegiones(){
		$query = $this->db->query("SELECT [Subgroup Code] AS region FROM [ASTURIANO].[dbo].[COORPORACION_EL_ASTURIANO\$Distribution Group Member] WHERE [Subgroup Code] LIKE 'REGION%' GROUP BY [Subgroup Code]");
		return $query->result();
	}

	# Obtener catalogo de zonas
	public function catZonas($region){
		if($region == null)
			$query = $this->db->query("SELECT [Subgroup Code] AS zona FROM [ASTURIANO].[dbo].[COORPORACION_EL_ASTURIANO\$Distribution Group Member] WHERE [Subgroup Code] LIKE 'zona%' GROUP BY [Subgroup Code]");
		/*else if(count($region) == 1 && $region[0] == '-')
			$query = $this->db->query("SELECT [Subgroup Code] AS zona FROM [ASTURIANO].[dbo].[COORPORACION_EL_ASTURIANO\$Distribution Group Member] WHERE [Subgroup Code] LIKE 'ZONA%' AND [Subgroup Code] NOT IN(SELECT [Subgroup Code] AS zona FROM [ASTURIANO].[dbo].[COORPORACION_EL_ASTURIANO\$Distribution Group Member] WHERE [Subgroup Code] LIKE 'zona%' AND [Distrib_ Loc_ Code] IN (SELECT [Distrib_ Loc_ Code] FROM [ASTURIANO].[dbo].[COORPORACION_EL_ASTURIANO\$Distribution Group Member] WHERE [Subgroup Code] IN (SELECT * FROM (SELECT [Subgroup Code] AS zona FROM [ASTURIANO].[dbo].[COORPORACION_EL_ASTURIANO\$Distribution Group Member] WHERE [Subgroup Code] LIKE 'REGION%' GROUP BY [Subgroup Code]) AS zonas))) GROUP BY [Subgroup Code]");*/
		else {
			$regiones = implode(',', $region);
			$regiones = "'" . str_replace(',', "','", $regiones) . "'";
			//if(strpos($regiones, "-") === false)
				$query = $this->db->query("SELECT [Subgroup Code] AS zona FROM [ASTURIANO].[dbo].[COORPORACION_EL_ASTURIANO\$Distribution Group Member] WHERE [Subgroup Code] LIKE 'ZONA%' AND [Distrib_ Loc_ Code] IN (SELECT [Distrib_ Loc_ Code] FROM [ASTURIANO].[dbo].[COORPORACION_EL_ASTURIANO\$Distribution Group Member] WHERE [Subgroup Code] IN ($regiones)) GROUP BY [Subgroup Code]");
			/*else
				$query = $this->db->query("SELECT [Subgroup Code] AS zona FROM [ASTURIANO].[dbo].[COORPORACION_EL_ASTURIANO\$Distribution Group Member] WHERE [Subgroup Code] LIKE 'ZONA%' AND [Subgroup Code] NOT IN(SELECT [Subgroup Code] AS zona FROM [ASTURIANO].[dbo].[COORPORACION_EL_ASTURIANO\$Distribution Group Member] WHERE [Subgroup Code] LIKE 'zona%' AND [Distrib_ Loc_ Code] IN (SELECT [Distrib_ Loc_ Code] FROM [ASTURIANO].[dbo].[COORPORACION_EL_ASTURIANO\$Distribution Group Member] WHERE [Subgroup Code] IN (SELECT * FROM (SELECT [Subgroup Code] AS zona FROM [ASTURIANO].[dbo].[COORPORACION_EL_ASTURIANO\$Distribution Group Member] WHERE [Subgroup Code] LIKE 'REGION%' GROUP BY [Subgroup Code]) AS zonas))) GROUP BY [Subgroup Code] UNION SELECT [Subgroup Code] AS zona FROM [ASTURIANO].[dbo].[COORPORACION_EL_ASTURIANO\$Distribution Group Member] WHERE [Subgroup Code] LIKE 'ZONA%' AND [Distrib_ Loc_ Code] IN (SELECT [Distrib_ Loc_ Code] FROM [ASTURIANO].[dbo].[COORPORACION_EL_ASTURIANO\$Distribution Group Member] WHERE [Subgroup Code] IN ($regiones)) GROUP BY [Subgroup Code]");*/
		}
		return $query->result();
	}

	# Obtener catalogo de tiendas
	public function catTiendas($zonas) {
		if($zonas == null)
			$query = $this->db->query("SELECT [Name], [No_] FROM [dbo].[COORPORACION_EL_ASTURIANO\$Store] WHERE [No_] IN (SELECT [Distrib_ Loc_ Code] FROM [ASTURIANO].[dbo].[COORPORACION_EL_ASTURIANO\$Distribution Group Member] WHERE [Subgroup Code] IN (SELECT [Subgroup Code] AS zona FROM [ASTURIANO].[dbo].[COORPORACION_EL_ASTURIANO\$Distribution Group Member] WHERE [Subgroup Code] LIKE 'zona%' GROUP BY [Subgroup Code]) group by [Distrib_ Loc_ Code])");
		else {
			$zonas = implode(',', $zonas);
			$zonas = "'" . str_replace(',', "','", $zonas) . "'";
			$query = $this->db->query("SELECT [Name], [No_] FROM [dbo].[COORPORACION_EL_ASTURIANO\$Store] WHERE [No_] IN (SELECT [Distrib_ Loc_ Code] FROM [ASTURIANO].[dbo].[COORPORACION_EL_ASTURIANO\$Distribution Group Member] WHERE [Subgroup Code] IN ($zonas) group by [Distrib_ Loc_ Code])");
		}
		return $query->result();
	}

	# Obtener catalogo de productos
	public function catProductos($term, $divisiones) {
		$whereDivisiones = '';
		if($divisiones != null) {
			$divisiones = "'" . str_replace(',', "','", implode(',', $divisiones)) . "'";
			$whereDivisiones = "[Division Code] in ($divisiones) AND";
		}
		$query = $this->db->query("SELECT No_ AS id, concat(No_, '-', Description) AS value FROM [dbo].[COORPORACION_EL_ASTURIANO\$Item] where $whereDivisiones (No_ like '%$term%' or Description like '%$term%')");
		return $query->result();
	}

	# Obtener catalogo de proveedores
	public function catProveedores() {
		$query = $this->db->query("SELECT No_ AS id, No_ + '-' + case Name when '' then 'Nombre no asignado' else Name end as text FROM [dbo].[COORPORACION_EL_ASTURIANO\$Vendor]");
		return $query->result();
	}

}