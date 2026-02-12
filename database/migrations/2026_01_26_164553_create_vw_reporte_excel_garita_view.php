<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("DROP VIEW IF EXISTS vw_reporte_excel_garita");
        DB::statement("
            CREATE VIEW vw_reporte_excel_garita AS
            SELECT 
                dcg.id 				AS `ID`,
                CASE dcg.tipo_movimiento 
                    WHEN 'E' THEN 'ENTRADA'
                    WHEN 'S' THEN 'SALIDA'
                    ELSE 'N/A'
                END AS `TIPO MOVIMIENTO`,
                cg.fecha 			AS `FECHA`,
                dcg.hora			AS `HORA`,
                CASE dcg.tipo_entidad 
                    WHEN 'P' THEN 'PERSONA'
                    WHEN 'V' THEN 'VEHÍCULO'
                END AS `TIPO ENTIDAD`,
                CASE dcg.tipo_documento 
                    WHEN 1 THEN 'DNI'
                    WHEN 2 THEN 'RUC'
                    ELSE 'N/A'
                END AS `TIPO DOCUMENTO`,
                dcg.documento 		AS `N° DOCUMENTO`,
                dcg.nombre 			AS `NOMBRE`,
                dcg.placa 			As `PLACA`,
                tv.nombre 			AS `TIPO VEHÍCULO`,
                tm.nombre 			AS `TIPO CARGA`,
                dcg.destino 		AS `DESTINO`,
                CASE cg.turno 
                    WHEN 0 THEN 'DÍA'
                    WHEN 1 THEN 'NOCHE'
                END AS `TURNO`,
                cg.unidad 			AS `UNIDAD`,
                dcg.ocurrencias 	AS `OCURRENCIAS`,
                u.id                AS `ID USUARIO`,
                u.name 				AS `REGISTRADO POR`,
                dcg.created_at 		AS `FECHA DE REGISTRO`,
                e.color
            FROM `detalle_control_garita` dcg 
            LEFT JOIN `control_garita` cg 	ON dcg.control_garita_id = cg.id
            LEFT JOIN `users` u 			ON dcg.usuario_id = u.id
            LEFT JOIN `tipo_mineral` tm 	ON dcg.tipo_mineral_id = tm.id
            LEFT JOIN `tipos_vehiculos` tv 	ON dcg.tipo_vehiculo_id = tv.id
            LEFT JOIN `etiquetas` e 		ON dcg.etiqueta_id = e.id
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("DROP VIEW IF EXISTS vw_reporte_excel_garita");
    }
};
