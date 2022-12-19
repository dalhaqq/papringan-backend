@extends('...template')
@section('content')
<div class="content">
    <div class="container">
        <div class="page-title">
            <h3>Tambah Produk</h3>
        </div>
        <form action="{{ route('products.store') }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">Tentang Produk</div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="name" class="form-label">Nama Produk</label>
                                <input name="name" placeholder="Nama Produk" class="form-control" value="{{ old('name') }}">
                                @if ($errors->has('name'))
                                <div class="invalid-feedback">{{ $errors->first('name') }}</div>
                                @endif
                            </div>
                            <div class="mb-3">
                                <label for="price" class="form-label">Harga Produk</label>
                                <div class="input-group mb-3">
                                    <span class="input-group-text">Rp</span>
                                    <input type="text" name="price" class="form-control" value="{{ old('price') }}" aria-label="Amount (to the nearest dollar)">
                                    <span class="input-group-text">,00</span>
                                    @if ($errors->has('price'))
                                    <div class="invalid-feedback">{{ $errors->first('price') }}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="description" class="form-label">Deskripsi Produk</label>
                                <textarea name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
                                @if ($errors->has('description'))
                                <div class="invalid-feedback">{{ $errors->first('description') }}</div>
                                @endif
                            </div>
                            <div class="mb-3">
                                <label for="image" class="form-label">Gambar Produk</label>
                                <input name="image" class="form-control" type="file" id="formFile">
                                @if ($errors->has('image'))
                                <div class="invalid-feedback">{{ $errors->first('image') }}</div>
                                @endif
                            </div>
                            <div class="mb-3">
                                <label for="stock" class="form-label">Stok Produk</label>
                                <input name="stock" placeholder="Stok Produk" class="form-control" value="{{ old('stock') }}">
                                @if ($errors->has('stock'))
                                <div class="invalid-feedback">{{ $errors->first('stock') }}</div>
                                @endif
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
                                    <input type="number" name="weight" value="{{ old('weight') }}" placeholder="Berat Produk" class="form-control">
                                    @if ($errors->has('weight'))
                                    <div class="invalid-feedback">{{ $errors->first('weight') }}</div>
                                    @endif
                                    <small class="form-text">Berat produk dalam gram.</small>
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label class="col-sm-2 form-label" for="dimension">Dimensi Produk</label>
                                <div class="col-sm-10" name="dimension">
                                    <div class="row">
                                        <div class="col-md-2">
                                            <input type="text" name="dimension_x" value="{{ old('dimension_x') }}" placeholder="Panjang" class="form-control">
                                            @if ($errors->has('dimension_x'))
                                            <div class="invalid-feedback">{{ $errors->first('dimension_x') }}</div>
                                            @endif
                                        </div>
                                        <div class="col-md-2">
                                            <input type="text" name="dimension_y" value="{{ old('dimension_y') }}" placeholder="Lebar" class="form-control">
                                            @if ($errors->has('dimension_y'))
                                            <div class="invalid-feedback">{{ $errors->first('dimension_y') }}</div>
                                            @endif
                                        </div>
                                        <div class="col-md-2">
                                            <input type="text" name="dimension_z" value="{{ old('dimension_z') }}" placeholder="Tinggi" class="form-control">
                                            @if ($errors->has('dimension_z'))
                                            <div class="invalid-feedback">{{ $errors->first('dimension_z') }}</div>
                                            @endif
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
                            <button type="submit" class="btn btn-primary">Tambah Produk</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection