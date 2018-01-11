<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Item extends CI_Model{
	public function __construct(){
		parent::__construct();
	}
	public function venta($fecha){
		$query = $this->db->query("select xx.*, valorInventarioPesos/ventaPromedio as diasDeInventario from (select sum(([Existencia Piezas] - VentasNoReg) * [Precio Venta]) as valorInventarioPesos, sum([Venta x Dia Pesos]) as ventaPromedio from [BDREPORTS].[dbo].[CTF\$Inventario] where Date = '$fecha') as xx");
		return $query->row();
	}
	public function divisiones($fecha){
		$query = $this->db->query("
			select
			'division' as tipo,
			'false' as _open,
			[Division Code] as division,
			'' as zona,
			'' as tienda,
			'' as producto,
			'' as descripcion,
			0 as PrecioVenta,
			sum(existenciaPiezas) as existenciaPiezas,
			sum(existenciaPesos) as existenciaPesos,
			sum(ventaPromedioDiaPiezas) as ventaPromedioDiaPiezas,
			sum(ventaPromedioDiaPesos) as ventaPromedioDiaPesos,
			avg(diasDeInventario) as diasDeInventario
			from (
			select [Division Code],
			[Precio Venta] as precioVenta,
			([Existencia Piezas] - VentasNoReg) as existenciaPiezas,
			(([Existencia Piezas] - VentasNoReg) * [Precio Venta]) as existenciaPesos,
			[Venta x Dia Piezas] as ventaPromedioDiaPiezas,
			[Venta x Dia Pesos] as ventaPromedioDiaPesos,
			case when [Venta x Dia Pesos] = 0 then 0 else (([Existencia Piezas] - VentasNoReg) * [Precio Venta]) / ([Venta x Dia Pesos]) 
end as diasDeInventario
			from [BDREPORTS].[dbo].[CTF\$Inventario] r left join [ASTURIANO].[dbo].[COORPORACION_EL_ASTURIANO\$Item] i on r.[Item No_] = i.[No_] where Date = '$fecha' )
			as divisiones
			group by [Division Code]
			order by [Division Code] asc
		");
		return $query->result();
	}
	public function zonas($fecha, $division){
		$query = $this->db->query("
			select
			'zona' as tipo,
			'false' as _open,
			'$division' as division,
			[Subgroup Code] as zona,
			'' as tienda,
			'' as producto,
			'' as descripcion,
			0 as PrecioVenta,
			sum(existenciaPiezas) as existenciaPiezas,
			sum(existenciaPesos) as existenciaPesos,
			sum(ventaPromedioDiaPiezas) as ventaPromedioDiaPiezas,
			sum(ventaPromedioDiaPesos) as ventaPromedioDiaPesos,
			avg(diasDeInventario) as diasDeInventario
			from (
			select [Subgroup Code],
			[Precio Venta] as precioVenta,
			([Existencia Piezas] - VentasNoReg) as existenciaPiezas,
			(([Existencia Piezas] - VentasNoReg) * [Precio Venta]) as existenciaPesos,
			[Venta x Dia Piezas] as ventaPromedioDiaPiezas,
			[Venta x Dia Pesos] as ventaPromedioDiaPesos,
			case when [Venta x Dia Pesos] = 0 then 0 else (([Existencia Piezas] - VentasNoReg) * [Precio Venta]) / ([Venta x Dia Pesos]) 
end as diasDeInventario
			from [BDREPORTS].[dbo].[CTF\$Inventario] r
			left join [ASTURIANO].[dbo].[COORPORACION_EL_ASTURIANO\$Item] i on r.[Item No_] = i.[No_]
			left join (select [Subgroup Code], [Distrib_ Loc_ Code] from [ASTURIANO].[dbo].[COORPORACION_EL_ASTURIANO\$Distribution Group Member] where [Subgroup Code] like 'ZONA%') as zt on r.[Store No_] = zt.[Distrib_ Loc_ Code]
			where Date = '$fecha' and [Division Code] = '$division'
			) as zonas
			group by [Subgroup Code]
			order by [Subgroup Code] asc
		");
		return $query->result();
	}
	public function tiendas($fecha, $division, $zona){
		$query = $this->db->query("
			select
			'tienda' as tipo,
			'false' as _open,
			'$division' as division,
			'$zona' as zona,
			[Distrib_ Loc_ Code] as tienda,
			'' as producto,
			'' as descripcion,
			0 as PrecioVenta,
			sum(existenciaPiezas) as existenciaPiezas,
			sum(existenciaPesos) as existenciaPesos,
			sum(ventaPromedioDiaPiezas) as ventaPromedioDiaPiezas,
			sum(ventaPromedioDiaPesos) as ventaPromedioDiaPesos,
			avg(diasDeInventario) as diasDeInventario
			from (
			select [Subgroup Code], [Distrib_ Loc_ Code],
			[Precio Venta] as precioVenta,
			([Existencia Piezas] - VentasNoReg) as existenciaPiezas,
			(([Existencia Piezas] - VentasNoReg) * [Precio Venta]) as existenciaPesos,
			[Venta x Dia Piezas] as ventaPromedioDiaPiezas,
			[Venta x Dia Pesos] as ventaPromedioDiaPesos,
			case when [Venta x Dia Pesos] = 0 then 0 else (([Existencia Piezas] - VentasNoReg) * [Precio Venta]) / ([Venta x Dia Pesos]) 
end as diasDeInventario
			from [BDREPORTS].[dbo].[CTF\$Inventario] r
			left join [ASTURIANO].[dbo].[COORPORACION_EL_ASTURIANO\$Item] i on r.[Item No_] = i.[No_]
			left join (select [Subgroup Code], [Distrib_ Loc_ Code] from [ASTURIANO].[dbo].[COORPORACION_EL_ASTURIANO\$Distribution Group Member] where [Subgroup Code] like 'ZONA%') as zt on r.[Store No_] = zt.[Distrib_ Loc_ Code]
			where Date = '$fecha' and [Division Code] = '$division' and [Subgroup Code] = '$zona'
			) as tiendas
			group by [Distrib_ Loc_ Code]
			order by [Distrib_ Loc_ Code] asc
		");
		return $query->result();
	}
	public function productos($fecha, $division, $zona, $tienda){
		$query = $this->db->query("
			select
			'producto' as tipo,
			'false' as _open,
			'$division' as division,
			'$zona' as zona,
			'$tienda' as tienda,
			[Item No_] as producto,
			'' as descripcion,
			avg(precioVenta) as PrecioVenta,
			sum(existenciaPiezas) as existenciaPiezas,
			sum(existenciaPesos) as existenciaPesos,
			sum(ventaPromedioDiaPiezas) as ventaPromedioDiaPiezas,
			sum(ventaPromedioDiaPesos) as ventaPromedioDiaPesos,
			avg(diasDeInventario) as diasDeInventario
			from (
			select [Subgroup Code], [Distrib_ Loc_ Code], [Item No_],
			[Precio Venta] as precioVenta,
			([Existencia Piezas] - VentasNoReg) as existenciaPiezas,
			(([Existencia Piezas] - VentasNoReg) * [Precio Venta]) as existenciaPesos,
			[Venta x Dia Piezas] as ventaPromedioDiaPiezas,
			[Venta x Dia Pesos] as ventaPromedioDiaPesos,
			case when [Venta x Dia Pesos] = 0 then 0 else (([Existencia Piezas] - VentasNoReg) * [Precio Venta]) / ([Venta x Dia Pesos]) 
end as diasDeInventario
			from [BDREPORTS].[dbo].[CTF\$Inventario] r
			left join [ASTURIANO].[dbo].[COORPORACION_EL_ASTURIANO\$Item] i on r.[Item No_] = i.[No_]
			left join (select [Subgroup Code], [Distrib_ Loc_ Code] from [ASTURIANO].[dbo].[COORPORACION_EL_ASTURIANO\$Distribution Group Member] where [Subgroup Code] like 'ZONA%') as zt on r.[Store No_] = zt.[Distrib_ Loc_ Code]
			where Date = '$fecha' and [Division Code] = '$division' and [Subgroup Code] = '$zona'and [Distrib_ Loc_ Code] = '$tienda'
			) as productos
			group by [Item No_]
			order by [Item No_] asc
		");
		return $query->result();
	}
	public function producto($producto){
		$query = $this->db->query("select top(1) Description from [dbo].[COORPORACION_EL_ASTURIANO\$Item] where [No_] = '$producto'");
		return $query->row();
	}
	public function tienda($tienda){
		$query = $this->db->query("select top(1) Name from [dbo].[COORPORACION_EL_ASTURIANO\$Store] where [No_] = '$tienda'");
		return $query->row();
	}
	public function tiendasDobleZona(){
		$query = $this->db->query("
			SELECT [No_] AS tienda, count([No_]) AS duplicados
			FROM [ASTURIANO].[dbo].[COORPORACION_EL_ASTURIANO\$Store] AS S
			LEFT JOIN [ASTURIANO].[dbo].[COORPORACION_EL_ASTURIANO\$Distribution Group Member] AS DGM
			ON(S.No_ = DGM.[Distrib_ Loc_ Code]) 
			WHERE DGM.[Subgroup Code] like 'ZONA%'
			GROUP BY No_
			HAVING count([No_])>1
		");
		return $query->result();
	}
	public function tiendasSinZona(){
		$query = $this->db->query("select * from (SELECT [No_] as tienda, [Name] as nombre, (SELECT top 1[Subgroup Code] FROM  [ASTURIANO].[dbo].[COORPORACION_EL_ASTURIANO\$Distribution Group Member] AS DGM WHERE S.No_ = DGM.[Distrib_ Loc_ Code] AND DGM.[Subgroup Code] like 'ZONA%') AS ZONA FROM [ASTURIANO].[dbo].[COORPORACION_EL_ASTURIANO\$Store] AS S WHERE No_ > 'T000') as ii where ZONA is null");
		return $query->result();
	}
}