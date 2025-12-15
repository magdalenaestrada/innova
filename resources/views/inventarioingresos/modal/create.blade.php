<div class="modal fade text-left" id="ModalCreate" role="dialog" aria-hidden="true">

    <div class="modal-dialog modal-lg" style="max-width: 85%;" role="document">
        <div class="modal-content">

            <div class="card-header">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="mt-2">
                            {{ __('CREAR ORDEN DE COMPRA') }}
                        </h6>
                    </div>
                    <div class="col-md-6 text-right">
                        <button type="button" style="font-size: 30px" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">x</span>
                        </button>
                    </div>
                </div>

            </div>

            <div class="card-body">
                <form method="POST" action="{{ route('inventarioingresos.store') }}">
                    @csrf

                    <table class="table">
                            <thead>
                            <tr>
                              
                                <th>
                                    {{ __('PRODUCTO') }}
                                </th>
                                
                                <th>
                                    {{ __('VALOR') }}
                                </th>
                                <th>
                                    {{ __('CANTIDAD') }}
                                </th>

                                <th>
                                    {{ __('IMAGEN') }}
                                </th>

                                <th>
                                    {{ __('SUBTOTAL') }}
                                </th>

                            
                                <th>
                                    <button class="btn btn-outline-dark pull-right" type="button" onclick="create_tr('table_body')" id="addMoreButton">
                                        <img style="width: 15px" src="{{asset('images/icon/mas.png')}}" alt="más">
                                    </button>
                                </th>


                            </tr>
                            </thead>

                            <tbody id="table_body">
                                <tr>
                                    
                                    <td >
                                        <select name="products[]" class="form-control buscador cart-product" style="width: 270px">
                                            <option selected disabled>{{ __('-- Seleccione una opción') }}</option>
                                            @foreach ($productos as $producto)
                                                <option value="{{ $producto->id }}"
                                                    {{ old('producto_id') == $producto->id ? 'selected' : '' }}>
                                                    {{ $producto->nombre_producto }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input id="product_price[]" name="product_price[]" class="form-control product-price"
                                        value="0.0"></input>
                                    </td>
                                    <td>
                                        <input name="qty[]" type="number" 
                                        class="form-control product-qty" value="0.0" >
                                    </td>
                                    <td>
                                        <img style="width: 50px" src="{{ asset( $producto->image) }}" alt="Producto" class="product-image">
                                    </td>
                                    <td>
                                        <input id="item_total" name="item_total[]" class="form-control product-total"></input>
                                    </td>
                                    <td>
                                        <button class="btn btn btn-danger" onclick="remove_tr(this)" type="button">Quitar</button>

                                    </td>
                                </tr>
                            </tbody>
                        

                        


                    </table>                        

                    <div class="form-group col-md-3 g-3 mb-3">
                            <label for="tipomoneda">
                                {{ __('TIPO MONEDA') }}
                            </label>
                            <span class="text-danger">(*)</span>
                            <select name="tipomoneda" id="tipomoneda"
                                class="form-select @error('tipomoneda') is-invalid @enderror form-control" aria-label="">
                                <option selected disabled>{{ __('-- Seleccione una opción') }}</option>

                                <option value="SOLES" {{ old('tipomoneda') == 'SOLES' ? 'selected' : '' }}>
                                    SOLES
                                </option>
                                <option value="DOLARES" {{ old('tipomoneda') == 'DOLARES' ? 'selected' : '' }}>
                                    DOLARES
                                </option>
                            </select>
                    </div>

                    <div class="row mb-3">
                            <div class="form-group col-md-4 g-3">
                                <label for="documento_proveedor" class="text-success">
                                    {{ __('RUC PROVEEDOR') }}
                                </label>
                                <span class="text-danger">(*)</span>
                                <div class="input-group">
                                    <input type="text" name="documento_proveedor" id="documento_proveedor"
                                        class="form-control @error('documento_proveedor') is-invalid @enderror"
                                        value="{{ old('documento_proveedor') }}" placeholder="ingrese DNI ó RUC">
                                    <button class="btn btn-primary" type="button" id="buscar_proveedor_btn">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                            viewBox="0 0 25 25" style="fill: rgba(255, 255, 255, 1);transform: ;msFilter:;">
                                            <path
                                                d="M19.023 16.977a35.13 35.13 0 0 1-1.367-1.384c-.372-.378-.596-.653-.596-.653l-2.8-1.337A6.962 6.962 0 0 0 16 9c0-3.859-3.14-7-7-7S2 5.141 2 9s3.14 7 7 7c1.763 0 3.37-.66 4.603-1.739l1.337 2.8s.275.224.653.596c.387.363.896.854 1.384 1.367l1.358 1.392.604.646 2.121-2.121-.646-.604c-.379-.372-.885-.866-1.391-1.36zM9 14c-2.757 0-5-2.243-5-5s2.243-5 5-5 5 2.243 5 5-2.243 5-5 5z">
                                            </path>
                                        </svg>
                                    </button>
                                </div>
                                @error('documento_proveedor')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-8 g-3">
                                <label for="datos_proveedor" class="">
                                    {{ __('NOMBRE Ó RAZÓN SOCIAL PROVEEDOR') }}
                                </label>
                                <span class="text-danger">(*)</span>
                                <input type="text" name="proveedor" id="datos_proveedor"
                                    class="form-control @error('datos_proveedor') is-invalid @enderror"
                                    value="{{ old('datos_proveedor') }}" placeholder="Datos obtenidos automáticamente...">
                                @error('datos_proveedor')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                    </div>

                    <div class="form-group col-md-12 g-3 mb-3">
                                <label for="telefono_proveedor" class="">
                                    {{ __('TELÉFONO DEL PROVEEDOR') }}
                                </label>
                                <span class="text-danger">(*)</span>
                                <input type="text" step="0.01" name="telefono_proveedor" id="stock" class="form-control @error('telefono_proveedor') is-invalid @enderror" value="{{ old('telefono_proveedor') }}" placeholder="Teléfono del proveedor...">
                                @error('telefono_proveedor')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                    </div>

                    <div class="form-group col-md-12 g-3">
                                <label for="direccion_proveedor" class="">
                                    {{ __('DIRECCIÓN DEL PROVEEDOR') }}
                                </label>
                                <span class="text-danger">(*)</span>
                                <input type="text" step="0.01" name="direccion_proveedor" id="direccion_proveedor" class="form-control @error('direccion_proveedor') is-invalid @enderror" value="{{ old('direccion_proveedor') }}" placeholder="Dirección del proveedor...">
                                @error('direccion_proveedor')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                    </div>                

                    <div class="d-flex justify-content-end mt-5">

                            <div class="col-md-3 text-right">

                                <span><b>subtotal</b></span>
                                <input id="product_sub_total" name="product_sub_total" class="product-sub-total"
                                    value="0.0"></input>

                            </div>

                            <div class="col-md-3 text-right">

                                <span><b>Total</b></span>

                                <input id="product_grand_total" name="product_grand_total" class="product-grand-total"
                                    value="0.0"></input>


                            </div>


                            <div class="col-md-2">

                                <button class="btn btn btn-primary pull-right" type="submit" id="saveOrder">Guardar</button>
                            </div>
                    </div>
                </form>
            </div>
            
        </div>        
    </div>
</div>
@section('js')
<script src="{{asset('js/interactive.js')}}"></script>
    
<script src="{{asset('js/packages/jquery.min.js')}}"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
            
                function updateRowTotal(row) {
                    var price = parseFloat(row.find('.product-price').val()) || 0;
                    var qty = parseFloat(row.find('.product-qty').val()) || 0;
                    var total = price * qty;
                    row.find('.product-total').val(total.toFixed(2));
                }

                function calculateGrandTotal() {
                    var subTotal = 0;
                    var grandTotal = 0;
                    $(".product-total").each(function() {
                        subTotal += parseFloat($(this).val()) || 0;
                    });
                    grandTotal = subTotal * 1.18
                    $("#product_sub_total").val(subTotal.toFixed(2));
                    $("#product_grand_total").val(grandTotal.toFixed(2));

                }


                $(document).on("change", ".cart-product, .product-qty, .product-price", function() {
                    var row = $(this).closest('tr'); 
                    updateRowTotal(row);
                    calculateGrandTotal();
                    
                });

                $(document).on("blur", ".barcode", function() {
                    var barcode = $(this).val();
                    var url = "{{ route('get.product.by.barcode', ['barcode' => ':barcode']) }}";
                    url = url.replace(':barcode', barcode);
                    var currentRow = $(this).closest('tr');

                    $.ajax({
                        url: url,
                        type: 'GET',
                        dataType: 'json',
                        success: function(response) {
                            if (response.success) {
                                var product = response.product;
                                currentRow.find('.cart-product').val(product
                                .id); // Assuming 'id' is the product ID field
                                productImage = currentRow.find('.product-image');
                                productImage.attr('src', product.image);
                                // You can also update other fields like product name, price, etc.
                            } else {
                                // Handle error: product not found
                            }
                        },
                        error: function(xhr, status, error) {
                            // Handle AJAX errors
                        }
                    });
                });





                $(document).on("change", ".cart-product", function() {
                    var product = $(this).val();
                    var url = "{{ route('get.product-image.by.product', ['product' => ':product']) }}";
                    url = url.replace(':product', product);
                    var currentRow = $(this).closest('tr');

                    $.ajax({
                        url: url,
                        type: 'GET',
                        dataType: 'json',
                        success: function(response) {
                            if (response.success) {
                                var product = response.product;
                                productImage = currentRow.find('.product-image');
                                productImage.attr('src', product.image);


                            } else {
                                // Handle error: product not found
                            }
                        },
                        error: function(xhr, status, error) {
                            // Handle AJAX errors
                        }
                    });
                });
            


                $(document).ready(function() {
                    $('.buscador').select2({theme: "classic"});
                });





                
    </script>


@stop

