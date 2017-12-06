<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Item extends CI_Model{
	public function __construct(){
		parent::__construct();
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
			0 as valor,
			0 as ventas,
			sum([Dias Inv]) as dias_de_inv
			from (
			select [Division Code], Existencia, [Dias Inv]
			from [BDREPORTS].[dbo].[CTF\$Inventario] r
			inner join [ASTURIANO].[dbo].[COORPORACION_EL_ASTURIANO\$Item] i on r.[Item No_] = i.[No_]
			where Date = '$fecha'
			) as divisiones group by [Division Code] order by [Division Code] asc
		");
		return $query->result();
	}
}