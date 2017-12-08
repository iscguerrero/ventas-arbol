<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Item extends CI_Model{
	public function __construct(){
		parent::__construct();
	}
	public function venta($fecha){
		$query = $this->db->query("select sum((Existencia - VentasNoReg) * [Precio Venta]) as ventas, avg([Dias Inv]) as dias_de_inv from [BDREPORTS].[dbo].[CTF\$Inventario] where Date = '$fecha'");
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
			sum(Existencia) as existencia,
			sum(valor) as valor,
			sum([Venta Periodo]) as ventas,
			avg([Dias Inv]) as dias_de_inv from (
			select [Division Code], Existencia - VentasNoReg as Existencia, (Existencia - VentasNoReg) * [Precio Venta] as valor, [Venta Periodo], [Dias Inv] from [BDREPORTS].[dbo].[CTF\$Inventario] r left join [ASTURIANO].[dbo].[COORPORACION_EL_ASTURIANO\$Item] i on r.[Item No_] = i.[No_] where Date = '$fecha' )
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
			sum(Existencia) as existencia,
			sum(valor) as valor,
			sum([Venta Periodo]) as ventas,
			avg([Dias Inv]) as dias_de_inv from (
			select [Subgroup Code], Existencia - VentasNoReg as Existencia, (Existencia - VentasNoReg) * [Precio Venta] as valor, [Venta Periodo], [Dias Inv]
			from [BDREPORTS].[dbo].[CTF\$Inventario] r
			left join [ASTURIANO].[dbo].[COORPORACION_EL_ASTURIANO\$Item] i on r.[Item No_] = i.[No_]
			left join (
			select [Subgroup Code], [Distrib_ Loc_ Code] from [ASTURIANO].[dbo].[COORPORACION_EL_ASTURIANO\$Distribution Group Member] where [Subgroup Code] like 'ZONA%'
			) as zt on r.[Store No_] = zt.[Distrib_ Loc_ Code]
			where Date = '$fecha' and [Division Code] = '$division'
			)
			as zonas
			group by [Subgroup Code]
			order by [Subgroup Code]
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
			sum(Existencia) as existencia,
			sum(valor) as valor,
			sum([Venta Periodo]) as ventas,
			avg([Dias Inv]) as dias_de_inv from (
			select [Subgroup Code], [Distrib_ Loc_ Code], Existencia - VentasNoReg as Existencia, (Existencia - VentasNoReg) * [Precio Venta] as valor, [Venta Periodo], [Dias Inv]
			from [BDREPORTS].[dbo].[CTF\$Inventario] r
			left join [ASTURIANO].[dbo].[COORPORACION_EL_ASTURIANO\$Item] i on r.[Item No_] = i.[No_]
			left join (
			select [Subgroup Code], [Distrib_ Loc_ Code] from [ASTURIANO].[dbo].[COORPORACION_EL_ASTURIANO\$Distribution Group Member] where [Subgroup Code] like 'ZONA%'
			) as zt on r.[Store No_] = zt.[Distrib_ Loc_ Code]
			where Date = '$fecha' and [Division Code] = '$division' and [Subgroup Code] = '$zona'
			)
			as tiendas
			group by [Distrib_ Loc_ Code]
			order by [Distrib_ Loc_ Code]
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
			sum(Existencia) as existencia,
			sum(valor) as valor,
			sum([Venta Periodo]) as ventas,
			avg([Dias Inv]) as dias_de_inv from (
			select [Subgroup Code], [Distrib_ Loc_ Code], [Item No_], Existencia - VentasNoReg as Existencia, (Existencia - VentasNoReg) * [Precio Venta] as valor, [Venta Periodo], [Dias Inv]
			from [BDREPORTS].[dbo].[CTF\$Inventario] r
			left join [ASTURIANO].[dbo].[COORPORACION_EL_ASTURIANO\$Item] i on r.[Item No_] = i.[No_]
			left join (
			select [Subgroup Code], [Distrib_ Loc_ Code] from [ASTURIANO].[dbo].[COORPORACION_EL_ASTURIANO\$Distribution Group Member] where [Subgroup Code] like 'ZONA%'
			) as zt on r.[Store No_] = zt.[Distrib_ Loc_ Code]
			where Date = '$fecha' and [Division Code] = '$division' and [Subgroup Code] = '$zona' and [Distrib_ Loc_ Code] = '$tienda'
			) as productos
			group by [Item No_]
			order by [Item No_]
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
}