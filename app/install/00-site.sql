CREATE TABLE IF NOT EXISTS empresa (
    id SERIAL PRIMARY KEY,
    nome VARCHAR NOT NULL UNIQUE CHECK (length(nome) > 1),
    url VARCHAR NOT NULL UNIQUE,
    logotipo VARCHAR NOT NULL,
    email VARCHAR NOT NULL CHECK (length(email) > 5),
    telefone VARCHAR,
    facebook VARCHAR,
    skype VARCHAR,
    instagram VARCHAR,
    twitter VARCHAR,
    idinterno VARCHAR UNIQUE,
    css VARCHAR,
    endereco VARCHAR,
    cep CHAR(10),
    cidade VARCHAR,
    estado CHAR(2),
    conteudosite TEXT, -- conteúdo da página empresa
    usuario INT NOT NULL REFERENCES usuario (id),
    ativo BOOL DEFAULT true,
    registro TIMESTAMP DEFAULT now()
);

CREATE TABLE IF NOT EXISTS cliente (
    id SERIAL PRIMARY KEY,
    empresa INT NOT NULL REFERENCES empresa (id),
    nome VARCHAR NOT NULL,
    email VARCHAR NOT NULL CHECK (length(email) > 5),
    sexo tipo_sexo,
    endereco VARCHAR,
    cidade VARCHAR,
    estado CHAR(2),
    cep VARCHAR,
    password VARCHAR NOT NULL,
    nascimento DATE,
    ativo BOOL DEFAULT true,
    registro TIMESTAMP DEFAULT now()
);

CREATE TABLE IF NOT EXISTS contatosite (
    id SERIAL PRIMARY KEY,
    empresa INT NOT NULL REFERENCES empresa (id),
    nome VARCHAR NOT NULL,
    email VARCHAR NOT NULL CHECK (length(email) > 5),
    telefone VARCHAR,
    endereco VARCHAR,
    cidade VARCHAR,
    estado CHAR(2),
    mensagem TEXT NOT NULL,
    visualizado TIMESTAMP,
    cliente INT REFERENCES cliente (id),
    ativo BOOL DEFAULT true,
    registro TIMESTAMP DEFAULT now()
);

CREATE TABLE IF NOT EXISTS faq (
  id SERIAL PRIMARY KEY,
  pergunta text,
  resposta text
);


-- Function: sem_acento(text)

-- DROP FUNCTION sem_acento(text);

CREATE OR REPLACE FUNCTION sem_acento(text)
  RETURNS text AS
$BODY$ 
    SELECT translate($1,'áàâãäéèêëíìïóòôõöúùûüÁÀÂÃÄÉÈÊËÍÌÏÓÒÔÕÖÚÙÛÜçÇ', 
                        'aaaaaeeeeiiiooooouuuuAAAAAEEEEIIIOOOOOUUUUcC'); 
$BODY$
  LANGUAGE sql IMMUTABLE STRICT
  COST 100;
ALTER FUNCTION sem_acento(text)
  OWNER TO postgres;