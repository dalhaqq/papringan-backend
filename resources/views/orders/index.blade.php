@extends('template')
@section('content')
<div class="content">
    <div class="container">
        <div class="page-title">
            <h3>Daftar Pesanan</h3>
        </div>
        <div class="row">
            <div class="col-md-12 col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <!-- filter status -->
                        <a href="{{ route('orders.index') }}" class="btn {{ $status ? 'btn-primary' : '' }}">Semua <span class="badge text-primary bg-light">{{ $counts['all'] }}</span></a>
                        <a href="{{ route('orders.index', ['status' => 'unpaid']) }}" class="btn {{ $status == 'unpaid' ? '' : 'btn-primary' }}">Belum Dibayar <span class="badge text-primary bg-light">{{ $counts['unpaid'] }}</span></a>
                        <a href="{{ route('orders.index', ['status' => 'paid']) }}" class="btn {{ $status == 'paid' ? '' : 'btn-primary' }}">Sudah Dibayar <span class="badge text-primary bg-light">{{ $counts['paid'] }}</span></a>
                        <a href="{{ route('orders.index', ['status' => 'shipped']) }}" class="btn {{ $status == 'shipped' ? '' : 'btn-primary' }}">Sedang Dikirim <span class="badge text-primary bg-light">{{ $counts['shipped'] }}</span></a>
                        <a href="{{ route('orders.index', ['status' => 'delivered']) }}" class="btn {{ $status == 'delivered' ? '' : 'btn-primary' }}">Sudah Diterima <span class="badge text-primary bg-light">{{ $counts['delivered'] }}</span></a>
                        <a href="{{ route('orders.index', ['status' => 'canceled']) }}" class="btn {{ $status == 'canceled' ? '' : 'btn-primary' }}">Dibatalkan <span class="badge text-primary bg-light">{{ $counts['canceled'] }}</span></a>
                    </div>
                    <div class="card-body">
                        <p class="card-title"></p>
                        <table class="table text-center" id="dataTables-example" width="100%">
                            <thead>
                                <tr>
                                    <th>Produk</th>
                                    <th>Harga Total</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($orders as $order)
                                <tr style="background-color: white;">
                                    <td>
                                        <!-- modalDetail -->
                                        <div id="modalDetail{{ $order->id }}" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h4 class="modal-title" id="myModalLabel">Detail Pesanan</h4>
                                                        <!-- badge status -->
                                                        @switch($order->status)
                                                        @case('unpaid')
                                                        <div class="mx-2 badge bg-warning">Belum Dibayar</div>
                                                        @break
                                                        @case('paid')
                                                        <div class="mx-2 badge bg-info">Sudah Dibayar</div>
                                                        @break
                                                        @case('shipped')
                                                        <div class="mx-2 badge bg-primary">Sedang Dikirim</div>
                                                        @break
                                                        @case('delivered')
                                                        <div class="mx-2 badge bg-success">Sudah Diterima</div>
                                                        @break
                                                        @case('canceled')
                                                        <div class="mx-2 badge bg-danger">Dibatalkan</div>
                                                        @break
                                                        @default
                                                        <div class="mx-2 badge bg-warning">Belum Dibayar</div>
                                                        @endswitch
                                                        <button class="btn-close" data-dismiss="modal" aria-hidden="true" onclick="tutup({{ $order->id }})"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <h5>Alamat Pengiriman</h5>
                                                                <p>
                                                                    {{ $order->customer->name }}<br>
                                                                    {{ $order->customer->address }}<br>
                                                                    {{ $order->customer->phone }}
                                                                </p>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <h5>Metode Pembayaran</h5>
                                                                <p>
                                                                    {{ $order->paymentMethod->name }}
                                                                </p>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <h5>Daftar Produk</h5>
                                                                <table class="table table-bordered">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>Produk</th>
                                                                            <th>Harga</th>
                                                                            <th>Jumlah</th>
                                                                            <th>Subtotal</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        @foreach($order->items as $item)
                                                                        <tr>
                                                                            <td>
                                                                                <img src="{{ $item->product->image }}" alt="" width="100">
                                                                                <br>
                                                                                {{ $item->product->name }}
                                                                            </td>
                                                                            <td>Rp{{ number_format($item->product->price, 0, ',', '.') }}</td>
                                                                            <td>{{ $item->quantity }}</td>
                                                                            <td>Rp{{ number_format($item->product->price * $item->quantity, 0, ',', '.') }}</td>
                                                                        </tr>
                                                                        @endforeach
                                                                    </tbody>
                                                                    <tfoot>
                                                                        <tr>
                                                                            <td colspan="3" class="text-right">Biaya Pengiriman</td>
                                                                            <td>Rp{{ number_format($order->shipping_cost, 0, ',', '.') }}</td>
                                                                        </tr>
                                                                        <tr style="font-weight: bold;">
                                                                            <td colspan="3" class="text-right">Total</td>
                                                                            <td>Rp{{ number_format($order->total(), 0, ',', '.') }}</td>
                                                                        </tr>
                                                                    </tfoot>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @switch($order->status)
                                                    @case('unpaid')
                                                    <div class="modal-footer">
                                                        <button type="submit" class="btn btn-primary" onclick="konfirmasiBayar({{ $order->id }})">Konfirmasi Pembayaran</borm>
                                                        <button type="submit" class="btn btn-danger" onclick="konfirmasiBatal({{ $order->id }})">Batalkan</button>
                                                    </div>
                                                    @break
                                                    @case('paid')
                                                    <div class="modal-footer">
                                                        <button type="submit" class="btn btn-primary" onclick="konfirmasiKirim({{ $order->id }})">Kirim</button>
                                                        <button type="submit" class="btn btn-danger" onclick="konfirmasiBatal({{ $order->id }})">Batalkan</button>
                                                    </div>
                                                    @break
                                                    @case('delivered')
                                                    @case('shipped')
                                                    @case('canceled')
                                                    @break
                                                    @default
                                                    <div class="modal-footer">
                                                        <button type="submit" class="btn btn-primary" onclick="konfirmasiBayar({{ $order->id }})">Konfirmasi Pembayaran</borm>
                                                        <button type="submit" class="btn btn-danger" onclick="konfirmasiBatal({{ $order->id }})">Batalkan</button>
                                                        @endswitch
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- modalKonfirmasiBayar -->
                                        <div class="modal fade" id="modalKonfirmasiBayar{{ $order->id }}" tabindex="-1" role="dialog" aria-labelledby="modalKonfirmasiBayar{{ $order->id }}Label" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="modalKonfirmasiBayar{{ $order->id }}Label">Konfirmasi Pembayaran</h5>
                                                        <button class="btn-close" data-dismiss="modal" aria-hidden="true" onclick="tutupBayar({{ $order->id }})"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        Anda yakin ingin mengkonfirmasi pembayaran?
                                                    </div>
                                                    <div class="modal-footer">
                                                        <form action="{{ route('orders.confirmPayment', ['order' => $order]) }}" method="POST">@csrf<button type="submit" class="btn btn-primary">Konfirmasi Pembayaran</button></form>
                                                        <button class="btn btn-secondary" data-dismiss="modal" onclick="tutupBayar({{ $order->id }})">Batal</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- modalKonfirmasiKirim -->
                                        <div class="modal fade" id="modalKonfirmasiKirim{{ $order->id }}" tabindex="-1" role="dialog" aria-labelledby="modalKonfirmasiKirim{{ $order->id }}Label" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="modalKonfirmasiKirim{{ $order->id }}Label">Konfirmasi Pengiriman</h5>
                                                        <button class="btn-close" data-dismiss="modal" aria-hidden="true" onclick="tutupKirim({{ $order->id }})"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        Anda yakin ingin mengkonfirmasi pengiriman?
                                                    </div>
                                                    <div class="modal-footer">
                                                        <form action="{{ route('orders.confirmPayment', ['order' => $order]) }}" method="POST">@csrf<button type="submit" class="btn btn-primary">Konfirmasi Pengiriman</button></form>
                                                        <button class="btn btn-secondary" data-dismiss="modal" onclick="tutupKirim({{ $order->id }})">Batal</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- modalKonfirmasiBatal -->
                                        <div class="modal fade" id="modalKonfirmasiBatal{{ $order->id }}" tabindex="-1" role="dialog" aria-labelledby="modalKonfirmasiBatal{{ $order->id }}Label" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="modalKonfirmasiBatal{{ $order->id }}Label">Konfirmasi Pembatalan</h5>
                                                        <button class="btn-close" data-dismiss="modal" aria-hidden="true" onclick="tutupBatal({{ $order->id }})"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        Anda yakin ingin mengkonfirmasi pembatalan?
                                                    </div>
                                                    <div class="modal-footer">
                                                        <form action="{{ route('orders.confirmPayment', ['order' => $order]) }}" method="POST">@csrf<button type="submit" class="btn btn-primary">Konfirmasi Pembatalan</button></form>
                                                        <button class="btn btn-secondary" data-dismiss="modal" onclick="tutupBatal({{ $order->id }})">Batal</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                @foreach($order->items as $item)
                                <tr style="background-color: #eee;">
                                    <td style="border: none;">
                                        <div style="float: left; padding-left: 40px;">
                                            {{ $item->product->name }}
                                            <br>
                                            <img src="{{ $item->product->image }}" alt="" width="100" style="text-align:center;">
                                        </div>
                                        <div>
                                            x {{ $item->quantity }}
                                        </div>
                                    </td>
                                    @if($loop->iteration == 1)
                                    <td rowspan="{{ $order->items->count() }}">
                                        Rp{{ number_format($order->total(), 0, ',', '.') }}
                                    </td>
                                    <td rowspan="{{ $order->items->count() }}">
                                        @switch($order->status)
                                        @case('unpaid')
                                        <span class="alert alert-danger">Belum Dibayar</span>
                                        @break
                                        @case('paid')
                                        <span class="alert alert-warning">Sudah Dibayar</span>
                                        @break
                                        @case('shipped')
                                        <span class="alert alert-info">Sedang Dikirim</span>
                                        @break
                                        @case('delivered')
                                        <span class="alert alert-success">Sudah Diterima</span>
                                        @break
                                        @case('canceled')
                                        <span class="alert alert-danger">Dibatalkan</span>
                                        @break
                                        @default
                                        <span class="alert alert-danger">Belum Dibayar</span>
                                        @endswitch
                                    </td>
                                    <td rowspan="{{ $order->items->count() }}">
                                        <!-- button for modal -->
                                        <button type="button" class="btn btn-primary" onclick="tampil({{ $order->id }})">
                                            Detail
                                        </button>
                                    </td>
                                    @endif
                                </tr>
                                @endforeach
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function tampil(id) {
        $(`#modalDetail${id}`).modal('show');
    }

    function tutup(id) {
        $(`#modalDetail${id}`).modal('hide');
    }

    function konfirmasiBayar(id) {
        $(`#modalDetail${id}`).modal('hide');
        $(`#modalKonfirmasiBayar${id}`).modal('show');
    }

    function tutupBayar(id) {
        $(`#modalKonfirmasiBayar${id}`).modal('hide');
        $(`#modalDetail${id}`).modal('show');
    }

    function konfirmasiKirim(id) {
        $(`#modalDetail${id}`).modal('hide');
        $(`#modalKonfirmasiKirim${id}`).modal('show');
    }

    function tutupKirim(id) {
        $(`#modalKonfirmasiKirim${id}`).modal('hide');
        $(`#modalDetail${id}`).modal('show');
    }

    function konfirmasiBatal(id) {
        $(`#modalDetail${id}`).modal('hide');
        $(`#modalKonfirmasiBatal${id}`).modal('show');
    }

    function tutupBatal(id) {
        $(`#modalKonfirmasiBatal${id}`).modal('hide');
        $(`#modalDetail${id}`).modal('show');
    }
</script>
@endsection