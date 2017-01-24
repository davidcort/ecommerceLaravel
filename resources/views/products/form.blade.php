{!!Form::open(["url"=> $url, "method"=> $method])!!}
            <div class="form-group">
                {{Form::text('title',$product->title,["class"=>"form-control","placeholder"=>"Título..."])}}
            </div>
            <div class="form-group">
                {{Form::number('pricing',$product->pricing,["class"=>"form-control","placeholder"=>"Precio en centavos de dolar"])}}
            </div>
            <div class="form-group">
                {{Form::textarea('description',$product->description,["class"=>"form-control","placeholder"=>"Descripción del producto"])}}
            </div>
            <div class="form-group text-right">
                <a href="{{url('/products')}}" class="">Regresar al listado de productos</a>
                <input type="submit" value="Enviar" class="btn btn-success">
            </div>
{!!Form::close() !!}