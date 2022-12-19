@extends('...template')
@section('content')
<div class="content">
    <div class="container">
        <div class="page-title">
            <h3>Edit Produk</h3>
        </div>
        <form action="{{ route('products.update', ['product'=>$product]) }}" method="post" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">Tentang Produk</div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="name" class="form-label">Nama Produk</label>
                                <input name="name" placeholder="Nama Produk" value="{{ $product->name }}" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="price" class="form-label">Harga Produk</label>
                                <div class="input-group mb-3">
                                    <span class="input-group-text">Rp</span>
                                    <input type="text" name="price" class="form-control" value="{{ $product->price }}" aria-label="Amount (to the nearest dollar)">
                                    <span class="input-group-text">,00</span>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="description" class="form-label">Deskripsi Produk</label>
                                <textarea name="description" class="form-control" rows="3">{{ $product->description }}</textarea>
                            </div>
                            <div class="mb-3">
                                <label for="image" class="form-label">Gambar Produk</label>
                                <br>
                                <img src="{{ $product->image }}" alt="" width="100" class="mb-3">
                                <input name="image" class="form-control" type="file" id="formFile">
                            </div>
                            <div class="mb-3">
                                <label for="stock" class="form-label">Stok Produk</label>
                                <input name="stock" placeholder="Stok Produk" class="form-control" value="{{ $product->stock }}" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">Ukuran Produk</div>
                        <div class="card-body">
                            <div class="mb-3 row">
                                <label class="col-sm-2 form-label" for="weight">Berat Produk</label>
                                <div class="col-sm-10">
                                    <input type="number" name="weight" placeholder="Berat Produk" class="form-control" value="{{ $product->weight }}" required>
                                    <small class="form-text">Berat produk dalam gram.</small>
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label class="col-sm-2 form-label" for="dimension">Dimensi Produk</label>
                                <div class="col-sm-10" name="dimension">
                                    <div class="row">
                                        <div class="col-md-2">
                                            <input type="text" name="dimension_x" value="{{ $product->dimension_x }}" placeholder="Panjang" class="form-control">
                                        </div>
                                        <div class="col-md-2">
                                            <input type="text" name="dimension_y" value="{{ $product->dimension_y }}" placeholder="Lebar" class="form-control">
                                        </div>
                                        <div class="col-md-2">
                                            <input type="text" name="dimension_z" value="{{ $product->dimension_z }}" placeholder="Tinggi" class="form-control">
                                        </div>
                                    </div>
                                    <small class="form-text">Panjang x Lebar x Tinggi (dalam mm)</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <button type="submit" class="btn btn-primary">Simpan Produk</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection