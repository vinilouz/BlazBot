## Documentação :

* [HTML](https://github.com/voltsdigital/documentation/wiki/HTML)
* [CSS](https://github.com/voltsdigital/documentation/wiki/CSS)
* [Javascript](https://github.com/voltsdigital/documentation/wiki/Javascript)
* [PHP](https://github.com/voltsdigital/documentation/wiki/PHP)
* [Banco de Dados](https://github.com/voltsdigital/documentation/wiki/Banco-de-Dados)
* [Diretórios](https://github.com/voltsdigital/documentation/wiki/Diretorios)
* [GIT](https://github.com/voltsdigital/documentation/wiki/GIT)
* [Testes](https://github.com/voltsdigital/documentation/wiki/Testes)
* [Schema.org](https://github.com/voltsdigital/documentation/wiki/Schema.org)

---

# Passo-a-passo de criação de Novos Site

###Instalação
* 1 - Criar base de dados 
    * Nome do Banco deve seguir o formato : __volts___ + 4 ou 5 primeiras letras do cliente/projeto, tudo em mínusculo.
    * Database Enconding : UTF-8 Unicode ( __utf8__ )
    * Database Collation : __utf8_general_ci__
* 2 - Criar repositório ( tudo  minúsculo )
* 3 - Baixar última  [versão do Worpdress]( https://wordpress.org/latest.zip) na pasta do projeto
* 4 - Copiar arquivos do __tema padrão__ para o diretório de temas
   * __Importante: verificar se sua versão de tema-wordpress está atualizada__

### Configuração do Tema
* 5 - Remover todos os temas (menos o que será utilizado)
* 6 - Remover a pasta .git do tema
* 7 - Mover o diretório `src` e os arquivos `readme.md`, `.jshintrc`, `.gitignore` e `.editorconfig` para a raiz do site
* 8 - Alterar o nome do diretório `wp-content` para `site`
* 9 - Copiar do arquivo `example-wp-config.php` a parte das URLs para o arquivo `wp-config.php`, logo abaixo da linha do DEBUG
* 10 -Remover o arquivo `example-wp-config.php`
* 11 -Copiar o conteúdo de `wp-config.php` para um novo arquivo com nome `wp-config2.php`
* 12- Criar uma imagem com a home do site na raiz do tema, nomeada como `screenshot.png`, com `763px` de largura, e no mínimo `572px`de altura; para aparecer em `Aparência > Temas` no painel.
 
### Finalização 
* Remover os dados padrão do WP (post, página e comentário).
* Remover plugin Hello Dolly
* Ativar plugin Akismet com essa chave: `78452d3116d1`
* Marcar para Akismet remover silenciosamente os spams
* Ativar o tema
* Remover descrição padrão ou colocar alguma que faça sentido
* Fuso horário para São Paulo
* Zerar todos os tamanhos de imagem (MINIATURA e GRANDE) para 0x0 , **menos o TAMANHO MÉDIO**, pois ele é usado pelo WP para gerar as miniaturas do painel.
* Links Permanentes :  **/%postname%/**
* Após isso :
    * Deslogar painel
    * Exportar base de dados na raiz do site 
    * Zipar
    * Fazer o commit __"Init commit"__

----- 

# Instalação das dependências para usar o Gulp com os módulos

### Configurações do GulpJS (diretório src)
* 1 - Editar no gulpconfig.js o `theme_name` com o nome do diretório do seu tema
* 2 -Instalar dependências para GulpJS, rodando o comando `npm install` ou `npm i` no diretório `src`.

### Configurações do tema (CSS, JS e imagens)
* Alterar o arquivo `style.css`, na raiz do tema, com as informações do projeto
* Criar logo para incluir na tela de login, no painel. Configurações de CSS (para alterar largura e altura, se necessário), estão no arquivo `functions/controllers/controller-common-admin.php`, no método `page_login_logo()` dentro do diretório do tema

---

## Instalando o NodeJS e GulpJS

Para instalar o NodeJS, instruções e download aqui: [http://nodejs.org/download/](http://nodejs.org/download/)
Com o Node instalado, instale o GulpJS com o comando:
`npm install -g gulp` (Se instalou o Node como root, vai precisar usar **sudo**).

Agora você deve ter o NodeJS e o Gulp instalados e prontos para trabalhar!
> PS.: Os comandos `node` e `gulp` devem estar disponíveis na sua linha de comando.

----

## Configuração do Gulp

Baixe os pacotes necessários para usar o Gulp com o comando: `npm i` no diretório `src` do projeto. Será criado um diretório `node_modules` com os módulos necessários para executar o Gulp.

Após isso, edite o arquivo `src/gulpconfig.js`, editando as seguintes variáveis:
* `theme_name` => Nome do seu tema

---
