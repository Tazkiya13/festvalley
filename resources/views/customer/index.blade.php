@extends('admin.home')
@section('title', 'package List')
@section('content')
    <div class="app-body" id="view">
        <div class="page-content">
            <div class="row-col">
                <div class="col-lg-9 b-r no-border-md">
                    <div class="padding">
                        <div class="page-title m-b position-relative">
                            <div class="d-flex justify-content-center align-items-center" style="min-height: 80px; position: relative;">
                                <h1 class="m-0 text-center flex-grow-1">Browse</h1>
                                <div style="position: absolute; right: 0;">
                                    <button type="button" class="btn btn-light" data-toggle="modal" data-target="#search-modal">
                                        <span class="nav-icon"> Search <i class="material-icons">search</i></span>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div data-ui-jp="jscroll" class="jscroll-loading-center"
                            data-ui-options="{
            autoTrigger: true,
            loadingHtml: '<i class=\'fa fa-refresh fa-spin text-md text-muted\'></i>',
            padding: 50,
            nextSelector: 'a.jscroll-next:last'
          }">
                            <div class="row">
                                @forelse ($packages as $package)
                                    <div class="col-xs-4 col-sm-4 col-md-3">
                                        <div class="item r" data-id="item-5" data-src="#">
                                            <div class="item-media"
                                                style="aspect-ratio:1/1; width:100%; border-radius:8px; overflow:hidden;">
                                                <a href="{{ route('card.detail', $package->id) }}" onclick="window.location=this.href; return false;" class="item-media-content"
                                                    style="display:block; width:100%; height:100%;">
                                                    <span
                                                        style="
                              display:block;
                              width:100%;
                              height:100%;
                              background-image: url('{{ $package->image ? asset('storage/' . $package->image) : asset('storage/default.png') }}');
                              background-size:cover;
                              background-position:center;
                              border-radius:8px;
                            "></span>
                                                </a>
                                                <div class="item-overlay center"></div>
                                            </div>
                                            <div class="item-info">
                                                <div class="item-overlay bottom text-right">
                                                    <a href="#" class="btn-favorite"><i class="fa fa-heart-o"></i></a>
                                                    <a href="#" class="btn-more" data-toggle="dropdown"><i
                                                            class="fa fa-ellipsis-h"></i></a>
                                                    <div class="dropdown-menu pull-right black lt"></div>
                                                </div>
                                                <div class="item-title text-ellipsis">
                                                    <a
                                                        href="{{ route('card.detail', $package->id) }}" onclick="window.location=this.href; return false;">{{ $package->package_name }}</a>
                                                </div>
                                                <div class="item-author text-sm text-ellipsis">
                                                    <a href="{{ route('card.detail', $package->id) }}" onclick="window.location=this.href; return false;"
                                                        class="text-muted">{{ $package->user->name }}</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                               @empty
                                <div class="col-12">
                                    <div class="alert alert-info text-center">Tidak ada package ditemukan.</div>
                                </div>
                                @endforelse
                            </div>
                            <div class="text-center"></div>
                        </div>
                        <div class="text-center" class="btn btn-sm white rounded jscroll-next">
                            {{ $packages->links('pagination::bootstrap-4') }}
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 w-xxl w-auto-md">
                    <div class="padding" style="bottom: 60px" data-ui-jp="stick_in_parent">
                        <h6 class="text text-muted">Recommand for you</h6>
                        <div class="row item-list item-list-sm m-b">
                            @php
                                $packages = $packages->shuffle();
                            @endphp
                            @foreach ($packages->take(5) as $package )
                            <div class="col-xs-12">
                                <div class="item r" data-id="{{ $package->id }}"
                                data-src="#">
                                <div class="item-media"><a href="{{ route('card.detail', $package->id) }}" onclick="window.location=this.href; return false;" class="item-media-content"
                                    style="background-image: url('{{ $package->image ? asset('storage/' . ltrim($package->image, '/')) : asset('images/default.jpg') }}')"></a></div>
                                    <div class="item-info">
                                        <div class="item-title text-ellipsis"><a href="{{ route('card.detail', $package->id) }}" onclick="window.location=this.href; return false;">{{ $package->package_name ?? 'Nama package' }}</a>
                                        </div>
                                        <div class="item-author text-sm text-ellipsis"><a href="{{ route('card.detail', $package->id) }}" onclick="window.location=this.href; return false;"
                                            class="text-muted">{{ $package->user->name ?? '' }}</a></div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>

    <div class="modal white lt fade" id="search-modal" data-backdrop="false">
        <a data-dismiss="modal" class="text-muted text-lg p-x modal-close-btn">&times;</a>
        <div class="row-col">
            <div class="p-a-lg h-v row-cell v-m">
                <div class="row">
                    <div class="col-md-8 offset-md-2">
                        <form id="searchFormModal" class="m-b-md" method="GET" action="{{ route('card.search') }}">
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <input type="text" class="form-control" name="artist" id="searchArtist"
                                        placeholder="Nama Artis">
                                </div>
                                 <div class="form-group col-md-4">
                                    <input type="text" class="form-control flatpickr-tanggal" name="tanggal"
                                        placeholder="Tanggal">
                                </div>
                                <div class="form-group col-md-4">
                                    <input type="text" class="form-control" name="package" id="searchpackage"
                                        placeholder="Nama package">
                                </div>
                            </div>
                            <div class="text-right">
                                <button class="btn btn-primary" type="submit">Search</button>
                            </div>
                        </form>
                        {{-- ...existing result preview code (optional, can be removed if only server search is needed)... --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

