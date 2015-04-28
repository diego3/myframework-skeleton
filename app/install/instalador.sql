-- -----------------------------------------------------
-- Table gestora
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS gestora (
  gestora_id INT NOT NULL,
  nome VARCHAR(60) NOT NULL,
  ativo BOOLEAN NOT NULL DEFAULT TRUE,
  PRIMARY KEY (gestora_id)
);
COMMENT ON TABLE gestora IS 'Gestora is responsible for contract manager';
COMMENT ON COLUMN gestora.gestora_id IS 'User ID number of gestora';
COMMENT ON COLUMN gestora.nome IS 'The gestora name';
COMMENT ON COLUMN gestora.ativo IS 'Used to logical exclusion, when it deleted the value is false';

-- -----------------------------------------------------
-- Table convitepersonalizado
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS convitepersonalizado (
  contrato INT NOT NULL,
  gestora_id INT NOT NULL,
  prazo DATE NOT NULL,
  eh_por_convidado BOOLEAN NOT NULL DEFAULT FALSE,
  observacao TEXT,
  concluido BOOLEAN NOT NULL DEFAULT FALSE,
  libera_upload_foto_pessoal BOOLEAN NOT NULL DEFAULT FALSE,
  bloqueado BOOLEAN NOT NULL DEFAULT TRUE,
  PRIMARY KEY (contrato),
  FOREIGN KEY (gestora_id) REFERENCES gestora (gestora_id)
);
COMMENT ON TABLE convitepersonalizado IS 'General data about the personalized product';
COMMENT ON COLUMN convitepersonalizado.contrato IS 'The number of contract that this product';
COMMENT ON COLUMN convitepersonalizado.gestora_id IS 'Gestora that manager the contrat of this product';
COMMENT ON COLUMN convitepersonalizado.prazo IS 'Deadline to student finishes his personalization';
COMMENT ON COLUMN convitepersonalizado.eh_por_convidado IS 'Defines if personalization is based in the guests';
COMMENT ON COLUMN convitepersonalizado.observacao IS 'General information guidance students';
COMMENT ON COLUMN convitepersonalizado.concluido IS 'The contract is finished and the system is blocked to edition';
COMMENT ON COLUMN convitepersonalizado.libera_upload_foto_pessoal IS 'The students must be upload personal photo';
COMMENT ON COLUMN convitepersonalizado.bloqueado IS 'When blocked all students of this contract cant use the system';

-- -----------------------------------------------------
-- Table formando
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS formando (
  formando_id SERIAL NOT NULL,
  contrato INT NOT NULL,
  usuario VARCHAR(45) NOT NULL,
  senha VARCHAR(45) NOT NULL,
  foto_principal VARCHAR(255),
  total_convidado INT NOT NULL DEFAULT 0,
  nome VARCHAR(60),
  pai VARCHAR(60),
  mae VARCHAR(60),
  cidade VARCHAR(60),
  estado CHAR(2),
  email VARCHAR(60),
  telefone VARCHAR(15),
  finalizado_em TIMESTAMP,
  primeiro_acesso_em TIMESTAMP,
  dados_pessoais_em TIMESTAMP,
  paginas_personalizadas_em TIMESTAMP,
  correcao_em TIMESTAMP,
  bloqueado BOOLEAN NOT NULL,
  PRIMARY KEY (formando_id),
  UNIQUE (contrato, usuario, senha),
  FOREIGN KEY (contrato) REFERENCES convitepersonalizado (contrato)
);
COMMENT ON TABLE formando IS 'About the student that is the main user of the personalized system';
COMMENT ON COLUMN formando.formando_id IS 'Auto generate student ID number';
COMMENT ON COLUMN formando.contrato IS 'The number of contract that this product';
COMMENT ON COLUMN formando.usuario IS 'Login name';
COMMENT ON COLUMN formando.senha IS 'Login password';
COMMENT ON COLUMN formando.foto_principal IS 'Path to the file that conatins the main photo of this student';
COMMENT ON COLUMN formando.total_convidado IS 'Number of guest that the student adquired';
COMMENT ON COLUMN formando.nome IS 'Student name';
COMMENT ON COLUMN formando.pai IS 'Student dad name';
COMMENT ON COLUMN formando.mae IS 'Student mom name';
COMMENT ON COLUMN formando.cidade IS 'Student city';
COMMENT ON COLUMN formando.estado IS 'Student state';
COMMENT ON COLUMN formando.email IS 'Student email';
COMMENT ON COLUMN formando.telefone IS 'Student phone number';
COMMENT ON COLUMN formando.finalizado_em IS 'When the student finished his personalization';
COMMENT ON COLUMN formando.primeiro_acesso_em IS 'When the student did the first login';
COMMENT ON COLUMN formando.dados_pessoais_em IS 'When the student filled the personal data';
COMMENT ON COLUMN formando.paginas_personalizadas_em IS 'When the student filled the personalized data';
COMMENT ON COLUMN formando.correcao_em IS 'When the quality department reviewed the student data';
COMMENT ON COLUMN formando.bloqueado IS 'When blocked the students cant use the system';


-- -----------------------------------------------------
-- Table cursogenerico
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS cursogenerico (
  cursogenerico_id SERIAL NOT NULL,
  nome VARCHAR(60) NOT NULL,
  banner_publicidade VARCHAR(60) NOT NULL,
  link_publicidade VARCHAR(120) NOT NULL,
  PRIMARY KEY (cursogenerico_id)
);
COMMENT ON TABLE cursogenerico IS 'General Course - not specific course';
COMMENT ON COLUMN cursogenerico.cursogenerico_id IS 'Auto generate course ID number';
COMMENT ON COLUMN cursogenerico.nome IS 'Course name';
COMMENT ON COLUMN cursogenerico.banner_publicidade IS 'Path to ad image';
COMMENT ON COLUMN cursogenerico.link_publicidade IS 'URL to send when the student click in it';


-- -----------------------------------------------------
-- Table cursogenerico_contrato
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS cursogenerico_contrato (
  cursogenerico_id INT NOT NULL,
  contrato INT NOT NULL,
  PRIMARY KEY (cursogenerico_id, contrato),
  FOREIGN KEY (cursogenerico_id) REFERENCES cursogenerico (cursogenerico_id)
    ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (contrato) REFERENCES convitepersonalizado (contrato)
    ON DELETE CASCADE ON UPDATE CASCADE
);
COMMENT ON TABLE cursogenerico_contrato IS 'The general courses of the contract';
COMMENT ON COLUMN cursogenerico_contrato.cursogenerico_id IS 'Course generic ID';
COMMENT ON COLUMN cursogenerico_contrato.contrato IS 'Contract number';


-- -----------------------------------------------------
-- Table paginapersonalizada
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS paginapersonalizada (
  contrato INT NOT NULL,
  numero_pagina INT NOT NULL,
  titulo VARCHAR(60) NOT NULL,
  eh_principal BOOLEAN NOT NULL ,
  eh_convidado BOOLEAN NOT NULL,
  limite_caracteres INT NOT NULL DEFAULT 1000,
  total_foto INT NOT NULL DEFAULT 0,
  tem_titulo BOOLEAN NOT NULL DEFAULT FALSE,
  tem_subtitulo BOOLEAN NOT NULL DEFAULT FALSE,
  ajuda_titulo VARCHAR(120),
  ajuda_subtitulo VARCHAR(120),
  observacao TEXT,
  PRIMARY KEY (contrato, numero_pagina),
  FOREIGN KEY (contrato) REFERENCES convitepersonalizado (contrato)
);
COMMENT ON TABLE paginapersonalizada IS 'Each page that the student must personalize';
COMMENT ON COLUMN paginapersonalizada.contrato IS 'Contract number';
COMMENT ON COLUMN paginapersonalizada.numero_pagina IS 'Page number to link with the publication';
COMMENT ON COLUMN paginapersonalizada.titulo IS 'The title (or subject) of the page (for student information)';
COMMENT ON COLUMN paginapersonalizada.eh_principal IS 'If this page is a main page (the student will be choice his personal data to show in the page)';
COMMENT ON COLUMN paginapersonalizada.eh_convidado IS 'If this page is personalized by the guest';
COMMENT ON COLUMN paginapersonalizada.limite_caracteres IS 'The maximum of chacteres that the message can be in this page';
COMMENT ON COLUMN paginapersonalizada.total_foto IS 'The maximum of photos the student can uploaded for this page';
COMMENT ON COLUMN paginapersonalizada.tem_titulo IS 'Define if the page has a customized title';
COMMENT ON COLUMN paginapersonalizada.tem_subtitulo IS 'Define if the page has a customized second title';
COMMENT ON COLUMN paginapersonalizada.ajuda_titulo IS 'Only if the tem_titulo is true, Help to student fill this field';
COMMENT ON COLUMN paginapersonalizada.ajuda_subtitulo IS 'Only if the tem_subtitulo is true, Help to student fill this field';
COMMENT ON COLUMN paginapersonalizada.observacao IS 'General information about this page for student';


-- -----------------------------------------------------
-- Table mensagempadrao
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS mensagempadrao (
  mensagempadrao_id SERIAL NOT NULL,
  titulo VARCHAR(120),
  texto TEXT NOT NULL,
  numero_caracteres INT NOT NULL,
  autor VARCHAR(60) NULL,
  hits INT NOT NULL DEFAULT 0,
  PRIMARY KEY (mensagempadrao_id)
);
COMMENT ON TABLE mensagempadrao IS 'Default messages that the students can use in this pages';
COMMENT ON COLUMN mensagempadrao.mensagempadrao_id IS 'Auto generate message ID number';
COMMENT ON COLUMN mensagempadrao.titulo IS 'Message Title, if there is';
COMMENT ON COLUMN mensagempadrao.texto IS 'Message text';
COMMENT ON COLUMN mensagempadrao.numero_caracteres IS 'Total of text chacteres';
COMMENT ON COLUMN mensagempadrao.autor IS 'Author text, if there is';
COMMENT ON COLUMN mensagempadrao.hits IS 'Number of times that the message was utilized (just for ranking list)';


-- -----------------------------------------------------
-- Table pagina_formando
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS pagina_formando (
  paginaformando_id CHAR(15) NOT NULL,
  formando_id INT NOT NULL,
  contrato INT NOT NULL,
  numero_pagina INT NOT NULL,
  mensagem TEXT NOT NULL,
  titulo VARCHAR(120),
  subtitulo VARCHAR(120),
  convidado VARCHAR(120),
  tem_pai BOOLEAN,
  tem_mae BOOLEAN,
  tem_email BOOLEAN,
  tem_telefone BOOLEAN,
  tem_cidade BOOLEAN,
  ultima_modificacao TIMESTAMP DEFAULT now(),
  PRIMARY KEY (paginaformando_id),
  FOREIGN KEY (formando_id) REFERENCES formando (formando_id)
    ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (contrato , numero_pagina)
    REFERENCES paginapersonalizada (contrato , numero_pagina)
    ON DELETE CASCADE ON UPDATE CASCADE
);
COMMENT ON TABLE pagina_formando IS 'Store the personalized page information';
COMMENT ON COLUMN pagina_formando.paginaformando_id IS 'This code is joined of the fields contrato+numero_pagina+formando+guestnumber';
COMMENT ON COLUMN pagina_formando.formando_id IS 'The student owner of this page';
COMMENT ON COLUMN pagina_formando.contrato IS 'The contract of this student (to optimize the queries)';
COMMENT ON COLUMN pagina_formando.numero_pagina IS 'Page number';
COMMENT ON COLUMN pagina_formando.mensagem IS 'Personal message';
COMMENT ON COLUMN pagina_formando.titulo IS 'Page title (only if the respective field paginapersonalizada.tem_titulo is true)';
COMMENT ON COLUMN pagina_formando.subtitulo IS 'Page second title (only if the respective field paginapersonalizada.tem_subtitulo is true)';
COMMENT ON COLUMN pagina_formando.convidado IS 'Guest name (only if the respective field paginapersonalizada.eh_convidado is true)';
COMMENT ON COLUMN pagina_formando.tem_pai IS 'If student dad name will be showed in this page';
COMMENT ON COLUMN pagina_formando.tem_mae IS 'If student mom name will be showed in this page';
COMMENT ON COLUMN pagina_formando.tem_email IS 'If student email will be showed in this page';
COMMENT ON COLUMN pagina_formando.tem_telefone IS 'If student phone number will be showed in this page';
COMMENT ON COLUMN pagina_formando.tem_cidade IS 'If student city and state will be showed in this page';
COMMENT ON COLUMN pagina_formando.ultima_modificacao IS 'Date time of the last student change';


-- -----------------------------------------------------
-- Table foto_pagina_formando
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS foto_pagina_formando (
  paginaformando_id CHAR(15) NOT NULL,
  ordem INT NOT NULL,
  formando_id INT NOT NULL,
  foto VARCHAR(255) NOT NULL,
  PRIMARY KEY (paginaformando_id, ordem),
    FOREIGN KEY (formando_id) REFERENCES formando (formando_id)
    ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (paginaformando_id) REFERENCES pagina_formando (paginaformando_id)
    ON DELETE CASCADE ON UPDATE CASCADE
);
COMMENT ON TABLE foto_pagina_formando IS 'Store the path of the images uploaded by the student';
COMMENT ON COLUMN foto_pagina_formando.paginaformando_id IS 'The page that the photo will be showed';
COMMENT ON COLUMN foto_pagina_formando.ordem IS 'Number of the photo in the page';
COMMENT ON COLUMN foto_pagina_formando.formando_id IS 'Student Id (to optimize the queries)';
COMMENT ON COLUMN foto_pagina_formando.foto IS 'Path to the photo file in the server';


-- -----------------------------------------------------
-- Table acaolog
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS acaolog (
  acaolog_id CHAR(5) NOT NULL,
  titulo VARCHAR(60) NOT NULL,
  descricao TEXT,
  icone VARCHAR(60),
  eh_interna BOOLEAN NOT NULL,
  PRIMARY KEY (acaolog_id)
);
COMMENT ON TABLE acaolog IS 'List of actions registered in the history of modification';
COMMENT ON COLUMN acaolog.acaolog_id IS 'Internal code of each action';
COMMENT ON COLUMN acaolog.titulo IS 'Action Name/title';
COMMENT ON COLUMN acaolog.descricao IS 'Action description';
COMMENT ON COLUMN acaolog.icone IS 'Action image icon representative';
COMMENT ON COLUMN acaolog.eh_interna IS 'If the action is internal then the student doesnt see this action in the log';


-- -----------------------------------------------------
-- Table logpersonalizada
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS logpersonalizada (
  logpersonalizada_id BIGSERIAL NOT NULL,
  acaolog_id CHAR(5) NOT NULL,
  contrato INT NOT NULL,
  formando_id INT,
  usuario INT,
  detalhe TEXT,
  registro TIMESTAMP NOT NULL DEFAULT now(),
  PRIMARY KEY (logpersonalizada_id),
  FOREIGN KEY (contrato) REFERENCES convitepersonalizado (contrato)
    ON DELETE NO ACTION ON UPDATE NO ACTION,
  FOREIGN KEY (formando_id) REFERENCES formando (formando_id)
    ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (acaolog_id) REFERENCES acaolog (acaolog_id)
    ON DELETE CASCADE ON UPDATE CASCADE
);
COMMENT ON TABLE logpersonalizada IS 'Maintain the modification log (auditory table)';
COMMENT ON COLUMN logpersonalizada.logpersonalizada_id IS 'Auto generate ID of the log';
COMMENT ON COLUMN logpersonalizada.acaolog_id IS 'Action ID';
COMMENT ON COLUMN logpersonalizada.contrato IS 'Log is about the contract (all students of this contract will see the log)';
COMMENT ON COLUMN logpersonalizada.formando_id IS 'The student that generated (or the log is about him) the log';
COMMENT ON COLUMN logpersonalizada.usuario IS 'The internal user that generated the log';
COMMENT ON COLUMN logpersonalizada.detalhe IS 'Specific Details about the action';
COMMENT ON COLUMN logpersonalizada.registro IS 'Date time of the log';

-- -----------------------------------------------------
-- Inserts acaolog table
-- -----------------------------------------------------
INSERT INTO acaolog (acaolog_id, titulo, eh_interna, icone, descricao) VALUES
    ('LOG1' , 'Primeiro Acesso', FALSE, 'login.png', 'Primeira vez que o formando entrou no sistema com seu usuário e senha fornecido pela comissão de formatura'),
    ('LOGIN' , 'Entrou no sistema', FALSE, 'login.png', 'O formando entrou no sistema com seu usuário e senha'),
    ('SAIU' , 'Saiu do sistema', FALSE, 'login.png', 'O formando saiu do sistema'),
    ('ETP1' , 'Salvou os dados pessoais', FALSE, 'dados.png', 'O formando salvou os dados pessoais'),
    ('ETP1U' , 'Alterou os dados pessoais', FALSE, 'dados.png', 'O formando alterou os dados pessoais'),
    ('ETP2' , 'Salvou os dados de uma p�gina', FALSE, 'dados.png', 'O formando salvou os dados de uma página'),
    ('ETP2U' , 'Alterou os dados de uma p�gina', FALSE, 'dados.png', 'O formando alterou os dados de uma página'),
    ('FTADD' , 'Adicionou uma foto', FALSE, 'foto.png', 'O formando adicionou uma nova foto a página'),
    ('FTUPD' , 'Alterou uma foto', FALSE, 'foto.png', 'O formando alterou uma foto da página'),
    ('FTDEL' , 'Removeu uma foto', FALSE, 'foto.png', 'O formando removeu uma foto da página'),
    ('ETP3' , 'Finalizou edição', FALSE, 'fim.png', 'O formando finalizou a edição das páginas personalizadas'),
    ('EMAIL' , 'Reenviou e-mail', FALSE, 'email.png', 'O formando reenviou o email de finalização'),
    ('CONT' , 'Sistema liberado', FALSE, 'inicio.png', 'Os convites personalizados foram liberados'),
    ('ETP3D' , 'Cancelado a finalização', FALSE, 'inicio.png', 'O sistema foi reaberto para edição'),
    ('COBF' , 'Alerta do prazo de finalização', FALSE, 'email.png', 'O sistema enviou um alerta com informações sobre os prazos'),
    ('MKT' , 'Formando clicou na publicidade', TRUE, NULL, 'O formando clicou no banner de publicidade'),
    ('EXCEL' , 'Realizado download da planilha', TRUE, NULL, 'Os dados foram enviados para a diagramação'),
    ('QUALI' , 'Correção/Verificação dos dados', TRUE, NULL, 'O departamento de correção verificou os dados do formando'),
    ('FIM' , 'Contrato finalizado', TRUE, NULL, 'Contrato enviado para a produção'),
    ('CONTU' , 'Dados do convite alterado', TRUE, NULL, 'Os dados do convite foram alterados pela gestora'),
    ('VISU' , 'Acesso a área do formando', TRUE, NULL, 'O usu�rio acessou o sistema no papel do formando'),
    ('DTUPD' , 'Prazo de finalização alterado', FALSE, 'data.png', 'O prazo para finalização foi alterado'),
    ('BLOCK' , 'Bloqueio do contrato', TRUE, NULL, 'O acesso ao contrato foi bloqueado temporariamente'),
    ('FREE' , 'Desbloqueio do contrato', TRUE, NULL, 'O acesso ao contrato foi liberado');