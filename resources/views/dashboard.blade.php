@extends('layouts.app', ['pageSlug' => 'dashboard'])

@section('content')
    <div class="row">
        <form class="card mx-3" method="post" action={{ route('noticias.store') }}>
            @csrf

            <div class="card-header">
                <h4 class="card-title mb-0">Nova notícia</h4>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label class="form-label" for="tituloNoticia">Título</label>
                    <input id="titulo" name="titulo" class="form-control" />
                </div>

                @error('titulo')
                    <p>{{ $message }}</p>
                @enderror

                <div class="form-group">
                    <label class="form-label" for="textoNoticia">Corpo da notícia</label>
                    <textarea id="corpo" name="corpo" class="form-control" rows="4"></textarea>
                </div>

                @error('corpo')
                    <p>{{ $message }}</p>
                @enderror

                <button type="submit" class="btn btn-fill btn-primary mt-2">
                    Adicionar notícia
                </button>
            </div>
        </form>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-0">
                <div class="card-header d-flex">
                    <h4 class="card-title mb-0">Notícias cadastradas</h4>

                    <div class="d-flex w-50 ml-auto">
                        @csrf
                        <input id="termoPesquisa"
                               class="form-control"
                               placeholder="Pesquisar notícia por título"
                        />

                        <button class="btn btn-link d-flex" type="submit" onclick="pesquisarNoticia()">
                            <i class="tim-icons icon-zoom-split my-auto"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    @if ($noticias->count())
                        <ul id="noticiasCadastradas" class="list-group">
                            @foreach ($noticias as $noticia)
                                <li class="list-group-item">
                                    <span>{{ $noticia->titulo }}</span>
                                    <p class="mt-2 mb-0">{{ $noticia->corpo }}</p>

                                    <div class="d-flex mt-1">
                                        <button type="button"
                                                class="btn btn-link p-0 mr-2"
                                                data-toggle="modal"
                                                data-target="#editarNoticia"
                                                onclick="preencherCamposModal({{ $noticia }})">
                                            Editar
                                        </button>

                                        <form method="post" action={{ route('noticias.destroy', $noticia) }}>
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit" class="btn btn-link p-0">Apagar</button>
                                        </form>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <div class="bg-primary text-white text-center p-3 rounded">
                            Nenhuma notícia encontrada!
                        </div>
                    @endif

                    <div id="containerPesquisa" style="display:none">
                        <div class="d-flex bg-primary text-white text-center px-2 py-1 rounded">
                            <span id="infoTermoPesquisa" class="font-weight-bold"></span>
                            <button class="btn btn-link text-white p-0 d-flex align-items-center ml-auto"
                                    onclick="limparPesquisa()">
                                <i class="tim-icons icon-simple-remove"></i>
                            </button>
                        </div>

                        <ul id="resultadosPesquisa" class="list-group mt-3"></ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editarNoticia" tabindex="-1" role="dialog" aria-labelledby="editarNoticiaLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editarNoticiaLabel">Editar notícia</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form method="post" action={{ route('noticias.update') }}>
                @csrf
                @method('PUT')

                <div class="modal-body">
                    <input id="idNoticia" name="id" hidden />

                    <div class="form-group">
                        <label for="recipient-name" class="col-form-label">Título</label>
                        <input id="editarTitulo" name="titulo" type="text" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="message-text" class="col-form-label">Corpo da notícia</label>
                        <textarea id="editarCorpo" name="corpo" class="form-control"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary ml-auto" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary ml-2">Salvar</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('js')
    <script>
        function preencherCamposModal(noticia) {
            $('#idNoticia').val(noticia.id);
            $('#editarTitulo').val(noticia.titulo);
            $('#editarCorpo').val(noticia.corpo);
        }

        function pesquisarNoticia() {
            var resultadosPesquisa = [];
            var termoPesquisa = $('#termoPesquisa').val();
            var noticias = $('#noticiasCadastradas').children().clone();

            for (var i = 0; i < noticias.length; i++) {
                if (noticias[i].children[0].innerText.includes(termoPesquisa)) {
                    resultadosPesquisa.push(noticias[i]);
                }
            }

            if ($('#containerPesquisa').attr('style').length > 0) {
                $('#noticiasCadastradas').slideToggle('fast');
                $('#resultadosPesquisa').empty();

                resultadosPesquisa.forEach((resultado) => $('#resultadosPesquisa').append(resultado));
                $('#containerPesquisa').slideDown('slow');
            } else {
                $('#containerPesquisa').slideToggle('fast');

                $('#resultadosPesquisa').empty();

                resultadosPesquisa.forEach((resultado) => $('#resultadosPesquisa').append(resultado));
                $('#containerPesquisa').slideToggle('fast');
            }

            resultadosPesquisa.length > 0
                ? $('#infoTermoPesquisa').text(`Resultados para "${termoPesquisa}"`)
                : $('#infoTermoPesquisa').text(`Nenhum resultado encontrado para "${termoPesquisa}"`);
        }

        function limparPesquisa() {
            $('#containerPesquisa').slideUp('fast');
            $('#noticiasCadastradas').slideToggle('slow');
            $('#termoPesquisa').val('');
        }
    </script>
@endpush
