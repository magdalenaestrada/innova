import React, { useState, Fragment } from 'react';

const LiquidacionEditView = ({ liquidacion, cuentas, motivos, volverUrl, csrfToken, tiposcomprobantes, clientes, sociedades }) => {
    const [cuentaId, setCuentaId] = useState(liquidacion?.salidacuenta?.cuenta?.id || '');
    const [motivoId, setMotivoId] = useState(liquidacion?.salidacuenta?.motivo?.id || '');
    const [tipoComprobanteId, setTipoComprobanteId] = useState(liquidacion?.salidacuenta?.tipocomprobante?.id || '');
    const [clienteId, setClienteId] = useState(liquidacion?.cliente_id || '');
    const [fecha, setFecha] = useState(liquidacion?.fecha ? new Date(liquidacion.fecha).toISOString().slice(0, 10) : '');
    const [comprobanteCorrelativo, setComprobanteCorrelativo] = useState(liquidacion?.salidacuenta?.comprobante_correlativo || '');
    const [nroOperacion, setNroOperacion] = useState(liquidacion?.salidacuenta?.nro_operacion || '');
    const [documentoRepresentante, setDocumentoRepresentante] = useState(liquidacion?.representante_cliente_documento || '');
    const [nombreRepresentante, setNombreRepresentante] = useState(liquidacion?.representante_cliente_nombre || '');
    const [descripcion, setDescripcion] = useState(liquidacion?.salidacuenta?.descripcion || '');
    const [liqDolares, setLiqDolares] = useState(liquidacion?.salidacuenta?.cuenta?.tipomoneda?.nombre == 'DOLARES' ? liquidacion.total : Math.round((liquidacion.total / liquidacion.tipo_cambio) * 100) / 100);
    const [tipoCambio, setTipoCambio] = useState(liquidacion?.tipo_cambio || 0);

    const [conversion, setConversion] = useState(liquidacion?.salidacuenta?.cuenta?.tipomoneda?.nombre == 'DOLARES' ? liquidacion.total * liquidacion.tipo_cambio : liquidacion.total);
    const [descuentoAdelantoDolares, setDescuentoAdelantoDolares] = useState(() => {
        const totalDolMonto = (liquidacion?.sociedad?.adelantos || [])
          .filter(adelanto => adelanto?.liquidacion?.id == liquidacion?.id)
          .reduce((acc, adelanto) => {
            let dol_monto = 0;
      
            if (adelanto?.salidacuenta?.cuenta?.tipomoneda?.nombre === 'DOLARES') {
              dol_monto = adelanto?.total_sin_detraccion ?? adelanto?.salidacuenta?.monto ?? 0;
            } else {
              const base = adelanto?.total_sin_detraccion ?? adelanto?.salidacuenta?.monto ?? 0;
              dol_monto = adelanto?.tipo_cambio
                ? Math.round((base / adelanto.tipo_cambio) * 100) / 100
                : 0;
            }
      
            return parseFloat(acc) + parseFloat(dol_monto);
          }, 0);
      
        return totalDolMonto;
      });

      
      const [descuentoAdelantoSoles, setDescuentoAdelantoSoles] = useState(() => {
        const totalSolMonto = (liquidacion?.sociedad?.adelantos || [])
            .filter((adelanto) => adelanto?.liquidacion?.id == liquidacion?.id)
            .reduce((acc, adelanto) => {
                let sol_monto = 0;
    
                if (adelanto?.salidacuenta?.cuenta?.tipomoneda?.nombre === 'DOLARES') {
                    const base = adelanto?.total_sin_detraccion ?? adelanto?.salidacuenta?.monto ?? 0;
                    sol_monto = base * (adelanto?.tipo_cambio ?? 0);
                } else {
                    sol_monto = adelanto?.total_sin_detraccion ?? adelanto?.salidacuenta?.monto ?? 0;
                }
    
                return parseFloat(acc) + parseFloat(sol_monto);
            }, 0);
    
        return totalSolMonto;
    });
    
    const [totalSinDetraccionDolares, setTotalSinDetraccionDolares] = useState(liquidacion?.salidacuenta?.cuenta?.tipomoneda?.nombre == 'DOLARES' ? liquidacion.total_sin_detraccion : Math.round((liquidacion.total_sin_detraccion / liquidacion.tipo_cambio) * 100) / 100);
    const [totalSinDetraccionSoles, setTotalSinDetraccionSoles] = useState(liquidacion?.salidacuenta?.cuenta?.tipomoneda?.nombre == 'DOLARES' ? liquidacion.total_sin_detraccion * liquidacion.tipo_cambio : liquidacion.total_sin_detraccion);

    const [importeFinalDolares, setImporteFinalDolares] = useState(liquidacion?.salidacuenta?.cuenta?.tipomoneda?.nombre == 'DOLARES' ? liquidacion?.salidacuenta?.monto : Math.round((liquidacion?.salidacuenta?.monto / liquidacion.tipo_cambio) * 100) / 100)
    const [importeFinalSoles, setImporteFinalSoles] = useState(liquidacion?.salidacuenta?.cuenta?.tipomoneda?.nombre == 'DOLARES' ? liquidacion?.salidacuenta?.monto * liquidacion.tipo_cambio : liquidacion?.salidacuenta?.monto)

    const [theresdetraction, setTheresDetraction] = useState(liquidacion.total_sin_detraccion ? true : false)
    const [otrosDescDolares, setOtrosDescDolares] = useState(liquidacion?.salidacuenta?.cuenta?.tipomoneda?.nombre == 'DOLARES' ? liquidacion.otros_descuentos || 0 : Math.round((liquidacion.otros_descuentos / liquidacion.tipo_cambio) * 100) / 100)
    const [otrosDescSoles, setOtrosDescSoles] = useState(liquidacion?.salidacuenta?.cuenta?.tipomoneda?.nombre == 'DOLARES' ? (liquidacion.otros_descuentos * liquidacion.tipo_cambio) || 0 : liquidacion.otros_descuentos || 0)
    return (
        <>
            <br />
            <div className="container ">
                <div className="card">
                    <div className="card-header">
                        <div className="d-flex justify-content-between align-items-center w-100 ">
                            <h5 className="mb-0">LIQUIDACIÓN: IN-{liquidacion.id}</h5>
                            <a href={volverUrl} className="btn btn-danger btn-sm">VOLVER</a>
                        </div>
                    </div>


                    <div className="card-body">
                        <form action={`/lqliquidaciones/${liquidacion.id}`} method="POST">
                            <input type="hidden" name="_token" value={csrfToken} />
                            <input type="hidden" name="_method" value="PUT" />

                            <div className="row g-3">
                                <div className="col-md-5">
                                    <label className="form-label">Cuenta</label>
                                    <select name='cuenta_id' className="form-control form-control-sm" value={cuentaId} onChange={(e) => setCuentaId(e.target.value)}>

                                        {cuentas.map(cuenta => <option key={cuenta.id} value={cuenta.id}>{cuenta.nombre}</option>)}
                                    </select>
                                </div>
                                <div className="col-md-5">
                                    <label  className="form-label">Motivo</label>
                                    <select name="motivo_id" className="form-control form-control-sm" value={motivoId} onChange={(e) => setMotivoId(e.target.value)}>

                                        {motivos.map(motivo => <option key={motivo.id} value={motivo.id}>{motivo.nombre}</option>)}
                                    </select>
                                </div>
                                <div className="col-md-2">
                                    <label  className="form-label">Fecha</label>
                                    <input type="date" name="fecha" className="form-control form-control-sm " value={fecha} onChange={(e) => setFecha(e.target.value)} />
                                </div>
                                <div className="col-md-3">
                                    <label  className="form-label">Tipo de Comprobante</label>
                                    <select name="tipo_comprobante_id" className="form-control form-control-sm" value={tipoComprobanteId} onChange={(e) => setTipoComprobanteId(e.target.value)}>
                                        <option value="">Seleccione el tipo</option>
                                        {tiposcomprobantes.map(tc => <option key={tc.id} value={tc.id}>{tc.nombre}</option>)}
                                    </select>
                                </div>
                                <div  className="col-md-3">
                                    <label className="form-label">Nro Comprobante</label>
                                    <input name="nro_comprobante" type="text" className="form-control form-control-sm" value={comprobanteCorrelativo} onChange={(e) => { const uppercaseValue = e.target.value.toUpperCase(); setComprobanteCorrelativo(uppercaseValue) }} />
                                </div>
                                <div className="col-md-3">
                                    <label className="form-label">Cliente</label>
                                    <select name='cliente_id' className="form-control form-control-sm" value={clienteId} onChange={(e) => setClienteId(e.target.value)}>
                                        <option value="">Seleccione el cliente</option>
                                        {clientes.map(cliente => <option key={cliente.id} value={cliente.id}>{cliente.nombre}</option>)}
                                    </select>
                                </div>
                                <div className="col-md-3">
                                    <label className="form-label">Nro Operación</label>
                                    <input name='nrooperacion' type="text" className="form-control form-control-sm" value={nroOperacion} onChange={(e) => { const uppercaseValue = e.target.value.toUpperCase(); setNroOperacion(uppercaseValue) }} />
                                </div>
                                <div className="col-md-4 ">
                                    <label className="form-label">Documento Representante</label>
                                    <div className='input-group form'>
                                        <input disabled type="text" className="form-control form-control-sm" value={documentoRepresentante} onChange={(e) => setDocumentoRepresentante(e.target.value)} />
                                        <button disabled className="btn btn-sm btn-success" type="button" style={{ width: "30.5px", height: "30.5px" }}
                                            id="buscar_beneficiario_btn">
                                            <svg
                                                xmlns="http://www.w3.org/2000/svg"
                                                width="15"
                                                height="15"
                                                viewBox="0 0 25 25"
                                                style={{ fill: "rgba(255, 255, 255, 1)" }}
                                            >
                                                <path
                                                    d="M19.023 16.977a35.13 35.13 0 0 1-1.367-1.384c-.372-.378-.596-.653-.596-.653l-2.8-1.337A6.962 6.962 0 0 0 16 9c0-3.859-3.14-7-7-7S2 5.141 2 9s3.14 7 7 7c1.763 0 3.37-.66 4.603-1.739l1.337 2.8s.275.224.653.596c.387.363.896.854 1.384 1.367l1.358 1.392.604.646 2.121-2.121-.646-.604c-.379-.372-.885-.866-1.391-1.36zM9 14c-2.757 0-5-2.243-5-5s2.243-5 5-5 5 2.243 5 5-2.243 5-5 5z"
                                                />
                                            </svg>

                                        </button>
                                    </div>

                                </div>
                                <div className="col-md-8">
                                    <label className="form-label">Nombre Representante</label>
                                    <input  disabled type="text" className="form-control form-control-sm" value={nombreRepresentante} onChange={(e) => setNombreRepresentante(e.target.value)} />
                                </div>
                                <div className="col-md-12">
                                    <label className="form-label">Descripción</label>
                                    <input name='descripcion' type="text" className="form-control" value={descripcion} onChange={(e) => { const uppercaseValue = e.target.value.toUpperCase(); setDescripcion(uppercaseValue) }} />
                                </div>
                                <div className="col-md-12">
                                    <label className="form-label">Sociedad</label>
                                    <input  type="text" value={liquidacion.sociedad.nombre} className="form-control" readOnly />
                                </div>
                                <div className='col-md-12'>
                                    <table>
                                        {liquidacion.sociedad.adelantos.map(

                                            (adelanto) => {
                                                let dol_monto;
                                                let sol_monto;


                                                if (adelanto.salidacuenta.cuenta.tipomoneda.nombre == 'DOLARES') {

                                                    if (adelanto.total_sin_detraccion) {

                                                        dol_monto = adelanto.total_sin_detraccion
                                                        sol_monto = adelanto.total_sin_detraccion * adelanto.tipo_cambio

                                                    } else {

                                                        dol_monto = adelanto.salidacuenta.monto
                                                        sol_monto = adelanto.salidacuenta.monto * adelanto.tipo_cambio

                                                    }






                                                } else {


                                                    if (adelanto.total_sin_detraccion) {
                                                        dol_monto = Math.round((adelanto
                                                            .total_sin_detraccion / adelanto
                                                                .tipo_cambio) *
                                                            100) / 100;
                                                        sol_monto = adelanto.total_sin_detraccion

                                                    } else {
                                                        dol_monto = Math.round((adelanto
                                                            .salidacuenta.monto / adelanto
                                                                .tipo_cambio) *
                                                            100) / 100;
                                                        sol_monto = adelanto.salidacuenta.monto
                                                    }
                                                }


                                                return (
                                                    <>
                                                        {adelanto?.cerrado ?

                                                            (
                                                                adelanto?.liquidacion?.id !== liquidacion?.id ? null :
                                                                    < tr className='bg-green'>
                                                                        <input checked={true} type="checkbox" value={adelanto.id} />
                                                                        <span>
                                                                            DOLARES: {dol_monto}, CAMBIO: {adelanto.tipo_cambio}, SOLES: {sol_monto},
                                                                            COMPROBANTE: {adelanto.salidacuenta.comprobante_correlativo
                                                                                ? adelanto.salidacuenta.comprobante_correlativo
                                                                                : '001-IN-' + adelanto.id}
                                                                        </span>
                                                                    </tr>
                                                            )
                                                            :
                                                            (
                                                                null
                                                                // < tr>
                                                                //     <input type="checkbox" value={adelanto.id} />
                                                                //     <span>
                                                                //         DOLARES: {dol_monto}, CAMBIO: {adelanto.tipo_cambio}, SOLES: {sol_monto},
                                                                //         COMPROBANTE: {adelanto.salidacuenta.comprobante_correlativo
                                                                //             ? adelanto.salidacuenta.comprobante_correlativo
                                                                //             : '001-IN-' + adelanto.id}
                                                                //     </span>
                                                                // </tr>
                                                            )
                                                        }
                                                    </>

                                                );
                                            }

                                        )}
                                    </table>


                                </div>




                                <div className="col-md-4">
                                    <label className="form-label">Liquidación en Dólares</label>
                                    <input name='liqdolares' type="text" className="form-control" value={liqDolares} onChange={(e) => {
                                        const value = e.target.value;
                                        const numericValue = value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');
                                        setLiqDolares(numericValue)
                                        setConversion(numericValue * tipoCambio)

                                        if(theresdetraction){
                                            setTotalSinDetraccionDolares(numericValue - descuentoAdelantoDolares)
                                            setTotalSinDetraccionSoles(numericValue*tipoCambio - descuentoAdelantoSoles)
                                            setImporteFinalDolares((numericValue - descuentoAdelantoDolares)*0.9 -otrosDescDolares)
                                            setImporteFinalSoles((numericValue*tipoCambio - descuentoAdelantoSoles)*0.9 -otrosDescSoles)
                                            
                                        }else{
                                            setImporteFinalDolares(numericValue - descuentoAdelantoDolares -otrosDescDolares)
                                            setImporteFinalSoles((numericValue*tipoCambio - descuentoAdelantoSoles) -otrosDescSoles)
                                        }


                                    }} />
                                </div>
                                <div className="col-md-4">
                                    <label className="form-label">Tipo Cambio</label>
                                    <input name='tipocambio' type="text" className="form-control" value={tipoCambio}  onChange={(e)=>{
                                        const value = e.target.value;
                                        const numericValue = value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');
                                        setTipoCambio(numericValue)
                                        setConversion(liqDolares*numericValue)


                                        if(theresdetraction){
                                            setTotalSinDetraccionDolares(liqDolares - descuentoAdelantoDolares)
                                            setTotalSinDetraccionSoles((liqDolares*numericValue - descuentoAdelantoSoles))
                                            setImporteFinalDolares((liqDolares - descuentoAdelantoDolares)*0.9 -otrosDescDolares)
                                            setImporteFinalSoles((liqDolares*numericValue - descuentoAdelantoSoles)*0.9 -otrosDescSoles)
                                            
                                        }else{
                                            setImporteFinalDolares(liqDolares - descuentoAdelantoDolares -otrosDescDolares)
                                            setImporteFinalSoles((liqDolares*numericValue - descuentoAdelantoSoles) -otrosDescSoles)
                                        }
                                       
                                    }}/>
                                </div>
                                <div className="col-md-4">
                                    <label className="form-label">Conversión</label>
                                    <input name='conversion' type="text" className="form-control" value={conversion} onChange={(e)=>{
                                        const value = e.target.value;
                                        const numericValue = value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');
                                        setConversion(numericValue)
                                        setLiqDolares(numericValue/tipoCambio)

                                        if(theresdetraction){
                                            setTotalSinDetraccionDolares(numericValue/tipoCambio - descuentoAdelantoDolares)
                                            setTotalSinDetraccionSoles(numericValue - descuentoAdelantoSoles)
                                            setImporteFinalDolares((numericValue/tipoCambio - descuentoAdelantoDolares)*0.9 -otrosDescDolares)
                                            setImporteFinalSoles((numericValue - descuentoAdelantoSoles)*0.9 -otrosDescSoles)
                                            
                                        }else{
                                            setImporteFinalDolares(numericValue/tipoCambio - descuentoAdelantoDolares -otrosDescDolares)
                                            setImporteFinalSoles((numericValue - descuentoAdelantoSoles) -otrosDescSoles)
                                        }

                                        
                                       
                                    }} />
                                </div>
                                <div className="col-md-6">
                                    <label className="form-label">Descuento por Adelanto USD</label>
                                    <input name='descuentoadeldol' type="text" readOnly className="form-control" value={descuentoAdelantoDolares} />
                                </div>
                                <div className="col-md-6">
                                    <label className="form-label">Descuento por Adelanto Soles</label>
                                    <input name='descuentoadelsol' type="text" readOnly className="form-control" value={descuentoAdelantoSoles} />
                                </div>
                                {theresdetraction ?
                                    <>
                                        <div className="col-md-6">
                                            <label className="form-label">Total sin detracción USD</label>
                                            <input type="text" className="form-control" disabled value={totalSinDetraccionDolares} />
                                        </div>
                                        <div className="col-md-6">
                                            <label className="form-label">Total sin detracción soles</label>
                                            <input type="text" className="form-control" disabled value={totalSinDetraccionSoles} />
                                        </div>
                                    </>
                                    :
                                    null
                                }



                                <div className="col-md-2">
                                    <label className="form-label">¿Detracción?</label>
                                    <div className="form-check mt-2">
                                    <label  className="form-check-label">
                                        <input  className="form-check-input" type="checkbox" checked={theresdetraction}  />
                                        Aplicar</label>
                                    </div>
                                </div>
                                <div className="col-md-5">
                                    <label className="form-label">Importe Final USD</label>
                                    <input name='importefinaldol' type="text" className="form-control" value={importeFinalDolares} onChange={(e)=>{
                                        const value = e.target.value;
                                        const numericValue = value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');
                                        setImporteFinalDolares(numericValue);
                                        setImporteFinalSoles(numericValue* tipoCambio);

                                    }} />
                                </div>
                                <div className="col-md-5">
                                    <label className="form-label">Importe Final Soles</label>
                                    <input name='importefinalsol' type="text" className="form-control" value={importeFinalSoles} onChange={(e)=>{
                                        const value = e.target.value;
                                        const numericValue = value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');
                                        setImporteFinalSoles(numericValue);
                                        setImporteFinalDolares(numericValue / tipoCambio);

                                    }} />
                                </div>
                                <div className="col-md-6">
                                    <label className="form-label">Otros Desc USD</label>
                                    <input name='otrosdescdol' type="text" className="form-control" value={otrosDescDolares} 
                                    onChange={(e)=>{
                                        const value = e.target.value;
                                        const numericValue = value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');
                                        setOtrosDescDolares(numericValue);
                                        setOtrosDescSoles(numericValue * tipoCambio);
                                        if(theresdetraction){
                                            setTotalSinDetraccionDolares(liqDolares - descuentoAdelantoDolares)
                                            setTotalSinDetraccionSoles(liqDolares*tipoCambio - descuentoAdelantoSoles)
                                            setImporteFinalDolares((liqDolares - descuentoAdelantoDolares)*0.9 -numericValue)
                                            setImporteFinalSoles((liqDolares*tipoCambio - descuentoAdelantoSoles)*0.9 -numericValue*tipoCambio)
                                            
                                        }else{
                                            setImporteFinalDolares(liqDolares - descuentoAdelantoDolares -numericValue)
                                            setImporteFinalSoles((liqDolares*tipoCambio - descuentoAdelantoSoles) -numericValue*tipoCambio)
                                        }


                                    }} />
                                </div>
                                <div className="col-md-6">
                                    <label className="form-label">Otros Desc Soles</label>
                                    <input name='otrosdescsol' type="text" className="form-control" value={otrosDescSoles}
                                    onChange={(e)=>{
                                        const value = e.target.value;
                                        const numericValue = value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');
                                        setOtrosDescSoles(numericValue);
                                        setOtrosDescDolares(numericValue / tipoCambio);

                                        if(theresdetraction){
                                            setTotalSinDetraccionDolares(liqDolares - descuentoAdelantoDolares)
                                            setTotalSinDetraccionSoles(liqDolares*tipoCambio - descuentoAdelantoSoles)
                                            setImporteFinalDolares((liqDolares - descuentoAdelantoDolares)*0.9 -numericValue / tipoCambio)
                                            setImporteFinalSoles((liqDolares*tipoCambio - descuentoAdelantoSoles)*0.9 -numericValue)
                                            
                                        }else{
                                            setImporteFinalDolares(liqDolares - descuentoAdelantoDolares -numericValue /tipoCambio)
                                            setImporteFinalSoles((liqDolares*tipoCambio - descuentoAdelantoSoles) -numericValue)
                                        }

                                    }}
                                    />
                                </div>
                            </div>

                            <div className="text-end mt-4">
                                <button type="submit" className="btn btn-secondary">Guardar</button>
                            </div>
                        </form>
                    </div>
                </div >
            </div >
            <br />
        </>

    );
};

export default LiquidacionEditView;
