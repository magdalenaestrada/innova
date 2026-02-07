<table>
    <thead>
        <tr>
            <th>Documento</th>
            <th>Nombre</th>
            <th>Estado</th>
            <th>R Info</th>
            <th>N° Contrato</th>
            <th>Inicio</th>
            <th>Fin</th>
            <th>Empresas</th>
            <th>Representantes</th>
            <th>Contactos</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($clientes as $c)
            <tr>
                <td>{{ $c->documento }}</td>
                <td>{{ $c->nombre }}</td>
                <td>{{ $c->estado }}</td>
                <td>{{ $c->r_info }}</td>

                <td>{{ optional($c->contrato)->numero_contrato }}</td>
                <td>{{ optional($c->contrato)->fecha_inicio_contrato }}</td>
                <td>{{ optional($c->contrato)->fecha_fin_contrato }}</td>

                <td>
                    @foreach (optional($c->contrato)->empresas ?? [] as $e)
                        {{ $e->empresa->nombre }} |
                    @endforeach
                </td>

                <td>
                    @foreach ($c->representantes as $r)
                        {{ $r->persona->nombre ?? '' }} |
                    @endforeach
                </td>

                <td>
                    @foreach ($c->contactos as $ct)
                        {{ $ct->celular }} |
                    @endforeach
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
