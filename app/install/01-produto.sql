CREATE TABLE IF NOT EXISTS categoria (
    id SERIAL PRIMARY KEY,
    nome VARCHAR NOT NULL UNIQUE CHECK (length(nome) > 1),
    descricao TEXT,
    titulo VARCHAR NOT NULL,
    imagem VARCHAR,
    icone VARCHAR,
    nomeclasse VARCHAR UNIQUE,
    colunasporpagina INT,
    ordem INT,
    idinterno VARCHAR UNIQUE,
    ativo BOOL DEFAULT true,
    registro TIMESTAMP DEFAULT now()
);

CREATE TABLE IF NOT EXISTS subcategoria (
    id SERIAL PRIMARY KEY,
    categoria INT NOT NULL REFERENCES categoria (id),
    nome VARCHAR NOT NULL CHECK (length(nome) > 1),
    descricao TEXT,
    titulo VARCHAR NOT NULL,
    icone VARCHAR,
    ordem INT,
    idinterno VARCHAR UNIQUE,
    ativo BOOL DEFAULT true,
    registro TIMESTAMP DEFAULT now(),
    UNIQUE (categoria, nome)
);

CREATE TABLE IF NOT EXISTS produto (
    id SERIAL PRIMARY KEY,
    subcategoria INT NOT NULL REFERENCES subcategoria (id),
    nome VARCHAR NOT NULL CHECK (length(nome) > 1),
    descricao TEXT,
    titulo VARCHAR NOT NULL,
    icone VARCHAR,
    precopadrao NUMERIC(9,2),
    video VARCHAR,
    hits INT DEFAULT 0,
    compras INT DEFAULT 0,
    ordem INT,
    idinterno VARCHAR,
    idtemplate VARCHAR,
    ativo BOOL DEFAULT true,
    registro TIMESTAMP DEFAULT now()
);
ALTER TABLE produto ADD COLUMN precofixo boolean;
ALTER TABLE produto ADD COLUMN ativarprecopromocional boolean DEFAULT false;
ALTER TABLE produto ADD COLUMN precopromocional NUMERIC(9,2) DEFAULT 0.00;
ALTER TABLE produto ADD COLUMN botaocomprar character varying;
ALTER TABLE produto ADD COLUMN textoparcela character varying;
CREATE TYPE status_produto AS ENUM ('oculto', 'indisponivel', 'ok');
ALTER TABLE produto ADD COLUMN status status_produto;

CREATE TABLE IF NOT EXISTS produtofoto (
    id SERIAL PRIMARY KEY,
    produto INT NOT NULL REFERENCES produto (id),
    thu VARCHAR NOT NULL,
    imagem VARCHAR NOT NULL
);

CREATE TABLE IF NOT EXISTS produtoempresa (
    produto INT NOT NULL REFERENCES produto (id),
    empresa INT NOT NULL REFERENCES empresa (id),
    preco NUMERIC(10,2), 
    PRIMARY KEY(produto, empresa)
);

CREATE TABLE banner (
  id serial NOT NULL PRIMARY KEY,
  empresa integer  REFERENCES empresa (id),
  nome varchar,
  fundo character(7),
  imagem varchar,
  url varchar,
  descricao varchar,
  ordem INT,
  ativo boolean DEFAULT true,
  registro timestamp NOT NULL DEFAULT now()
);

CREATE TABLE IF NOT EXISTS logcompra (
    id SERIAL PRIMARY KEY,
    sessionid VARCHAR(32), --Identificador unico da sessao (quando não há usuários não logados)
    usuario INT REFERENCES usuario (id), --codigo do usuario (para usuário logado no sistema)
    produto INT REFERENCES produto (id),
    efetivada boolean default false,
    registro TIMESTAMP DEFAULT now()
);
