<?php
function createCategorias() {
    echo '<li>CreateCategorias</li>';
    
    $dao = Factory::DAO('categoria');
    /* @var $dao CategoriaDAO */
    
    $subcategoria = array(
        array('nome' => '15,5 X 20,5', 'icone' => 'photobook-15-20.png', 'descricao' => 'descrição'),
        array('nome' => '22 X 30', 'icone' => 'photobook-22-30.png', 'descricao' => 'descrição'),
        array('nome' => '23 X 30,5', 'icone' => 'photobook-23-30.png', 'descricao' => 'descrição'),
        array('nome' => '24 X 30', 'icone' => 'photobook-24-30.png', 'descricao' => 'descrição'),
        array('nome' => '30 X 40', 'icone' => 'photobook-30-40.png', 'descricao' => 'descrição')
    );
    $subcategoria = array();
    
    $categorias = array(
        array(
            'nome' => 'Photobook',
            'descricao' => "Photobooks são álbuns que você pode personalizar com suas fotos preferidas.\n"
                . "Aqui você encontra a maior diversidade de tamanhos e acabamentos para o seu Photobook.",
            'classe' => 'photobook',
            'subcategorias' => $subcategoria
        ),
        array(
            'nome' => 'Foto Presente',
            'descricao' => "Fotopresente é a maneira ideal de dar um toque especial em todos os ambientes de sua casa, surpreendendo sua família e amigos com as fotos de seus momentos especiais.\n"
                . " Aqui você encontra a maior variedade de tamanhos e acabamentos para sua fotopresente.",
            'classe' => 'fotopresente',
            'subcategorias' => $subcategoria
        ),
        array(
            'nome' => 'Calendários',
            'descricao' => "Aqui você pode personalizar o seu calendário colocando suas fotos preferidas para lembrar suas datas mais marcantes e especiais.\n"
                . "Aqui você encontra a maior diversidade de tamanhos e acabamentos para seu Calendário.",
            'classe' => 'calendario',
            'subcategorias' => $subcategoria
        ),
        array(
            'nome' => 'Convite',
            'descricao' => "O convite é a primeira impressão que o seu convidado terá sobre o momento especial que você está prestes a viver, é ele quem vai causar as expectativas para o instante da realização de seu sonho.\n"
                . "Aqui você encontra a maior diversidade de tamanhos e acabamentos para o seu Convite.",
            'classe' => 'convite',
            'subcategorias' => $subcategoria
        ),
        array(
            'nome' => 'Formatura',
            'descricao' => "Aqui você encontra tudo o que você precisa para deixar sua formatura com a sua cara, personalizando todos os produtos como você quiser.\n"
                . "Temos a maior diversidade de tamanhos e acabamentos para os produtos e adereços de sua formatura.",
            'classe' => 'formatura',
            'subcategorias' => $subcategoria
        ),
        array(
            'nome' => 'Foto Cartão',
            'descricao' => "A Foto Cartão é ideal para ser enviada para uma pessoa querida que esteja longe, para contar sobre seus momentos especiais, passeios, viagens ou aniversários.\n"
                . "Aqui você encontra a maior diversidade em tamanhos e acabamentos para sua Foto Cartão.",
            'classe' => 'fotocartao',
            'subcategorias' => $subcategoria
        ),
        array(
            'nome' => 'Gráfica',
            'descricao' => "Aqui você encontra tudo o que necessita de materiais gráficos, para seu uso pessoal ou até mesmo para presentear alguém, com a melhor qualidade e diversidade de tamanhos e acabamentos.",
            'classe' => 'grafica',
            'subcategorias' => $subcategoria
        ),
        array(
            'nome' => 'Kit Personalizado',
            'descricao' => "Os kits personalizados são brindes perfeitos para sua empresa, ou como lembrança de aniversários, casamentos e datas especiais.\n"
                . "Aqui você encontra a maior diversidade em tamanhos e acabamentos para seu kit personalizado.",
            'classe' => 'kitpersonalizado',
            'subcategorias' => $subcategoria
        ),
        array(
            'nome' => 'Revelação de Fotos',
            'descricao' => "Não deixe seus melhores momentos apenas na memória, revele agora suas fotos para eternizá-los.\n"
                . "Aqui você encontra a maior diversidade em tamanhos para revelar suas fotos.",
            'classe' => 'revelacaofoto',
            'subcategorias' => $subcategoria
        )
    );
    
    $subcategDAO = Factory::DAO('subcategoria');
    /* @var $subcategDAO SubcategoriaDAO */
    
    foreach ($categorias as $ordem => $dados) {
        $categ = $dao->getByNome($dados['nome']);
        if (!empty($categ)) {
            continue;
        }
        $imagem = 'image/categorias/' . $dados['classe'] . '.jpg';
        $icon = 'image/categorias/' . $dados['classe'] . '-icon.jpg';
        assert(
            $dao->novo($dados['nome'], $dados['descricao'], $dados['nome'], $imagem, $icon, $dados['classe'], 2, $ordem + 1, 'ID_' . $ordem)
        );
    }
    
    $cem = 0;
    foreach ($categorias as $ordem => $dados) {
        $categ = $dao->getByNome($dados['nome']);
        foreach ($dados['subcategorias'] as $i => $subcateg) {
            $subcategoria = $subcategDAO->getByNome($categ['id'], $subcateg['nome']);
            if (!empty($subcategoria)) {
                continue;
            }
            $titulo = $dados['nome'] . ' ' . $subcateg['nome'];
            assert(
                $subcategDAO->novo(
                    $subcateg['nome'],
                    $subcateg['descricao'],
                    $titulo,
                    $categ['id'],
                    $subcateg['icone'],
                    $i + ($i * $cem),
                    null
                )
            );
            $cem += 100;
        }
    }
} createCategorias();

function createProdutos() {
    echo '<li>CreateProdutos</li>';
    //Gerador de produtos aleatórios para testes
    $dao = Factory::DAO('produto');
    /*@var $dao ProdutoDAO */
    $subcategDAO = Factory::DAO('subcategoria');
    /*@var $subcategDAO SubcategoriaDAO */
    $lista = $subcategDAO->listAll(array(), 0, 1000);
    foreach ($lista as $ordem => $categ) {
        for ($i = 0; $i < 10; $i++) {
            $nome = $categ['titulo'] . ' Produto ' . $i;
            $produto = $dao->getByNome($nome);
            if (!empty($produto)) {
                continue;
            }            
            assert(
                $dao->novo(
                    $nome,
                    $categ['id'],
                    'Descrição genérica do produto',
                    $nome,
                    'image/produtos/produto-photobook.jpg',
                    rand(30, 100) . '.00',
                    'http://vimeo.com/32796535',
                    ($ordem*10) + $i,
                    'ID_' . ($ordem*10) + $i * rand()
                )
            );
        }
    }
} //createProdutos();

function createFotoproduto() { 
    echo '<li>CreateFotoprodutos</li>';
    //Gerador de produtos aleatórios para testes
    $dao = Factory::DAO('produto');
    $daofoto = Factory::DAO('produtofoto');
    $produtos = $dao->listAll(array(), 0, 1000);
    foreach ($produtos as $prod) {
        $fotos = $daofoto->listaByProduto($prod['id']);
        if (!empty($fotos)) {
            continue;
        }
        for ($i=1; $i < 6; $i++) {
            $img = 'image/produtos/photobook-0' . $i . '.jpg';
            $thu = 'image/produtos/photobook-0' . $i . '-thumb.jpg';
            assert($daofoto->novo($prod['id'], $img, $thu));
        }
    }
} //createFotoproduto();