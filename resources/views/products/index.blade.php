@extends('template')
@section('content')
<div class="content">
    <div class="container">
        <div class="page-title">
            <h3>Daftar Produk</h3>
        </div>
        <div class="row">
            <div class="col-md-12 col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <!-- tambah -->
                        <a href="{{ route('products.create') }}" class="btn btn-primary">Tambah Produk</a>
                    </div>
                    <div class="card-body">
                        <p class="card-title"></p>
                        <table class="table table-hover text-center" id="dataTables-example" width="100%">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Produk</th>
                                    <th>Harga</th>
                                    <th>Stok</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($products as $product)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <a href="{{ route('products.show', $product->id) }}">{{ $product->name }}</a>
                                        <br>
                                        <img src="{{ $product->image }}" alt="" width="100">
                                    </td>
                                    <td>{{ $product->price }}</td>
                                    <td>{{ $product->stock }}</td>
                                    <td>
                                        <a href="{{ route('products.edit', $product->id) }}" class="btn btn-warning">Edit</a>
                                        <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection