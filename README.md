Template do site para eventos do Tchelinux
==========================================

# Sobre este documento

Este é um template para sites de eventos do Tchelinux com uma grade com 5 horários e 5 salas em paralelo. O arquivo `index.html` pode ser totalmente customizado para ser adequado a quantidade de salas e horários de palestras.

# Variáveis

Para facilitar a criação do site todos os items variáveis estão identificados e podem ser facilmente substituidos em um editor de texto, ou um shell script. 

A seguir temos a descrição de todas as variáveis e exemplos de valores a serem usados:

## Dados do Evento

As variáveis que definem detalhes do evento são descritas na tabela abaixo:

| Variável                   | Significado                                 | Exemplo                                      |
| ---------------------------| --------------------------------------------|----------------------------------------------|
| $ANO	 	                 | Ano que o evento acontecerá                 | 2017                                         |
| $CIDADE                    | Cidade onde o evento acontecerá             | Porto Alegre                                 |
| $DATA                      | Data do evento                              | 28 de Maio de 2017                           |
| $INSTITUICAO               | Nome da Instituição sede do evento          | Faculdade SENAC Porto Alegre                 |
| $LOGO_INSTITUICAO          | Logotipo da Instituição sede do evento      | `images/logo_senac.png`                      | 
| $ENDERECO                  | Endereço da Instituição                     | Rua Coronel Genuíno, 130 - Centro Histórico  |
| $HORARIO                   | Horário do início do evento                 | 08:30                                        | 
| $URL_INSCRICAO             | URL do formulário de inscrição              | https://goo.gl/forms/abcd1234                |
| $PRAZO_CALL4PAPERS         | Data final da chamada de trabalhos          | 29 de Abril de 2017                          |
| $ANUNCIO_GRADE_FINAL       | Data de publicação da programação           | 02 de Maio de 2017                           |
| $URL_CALL4PAPERS           | URL do formulário da chamada de trabalhos   | https://goo.gl/forms/efgh5678                |
| $INSTITUICAO_CARIDADE      | Nome da Instituição que receberá as doações | Lar do Idoso                                 | 
| $LOCAL_RECEPCAO            | Local do credenciamento                     | 9º Andar                                     |
| $HORARIO_ABERTURA          | Horário da abertura do evento               | 09:00                                        |
| $HORARIO_ALMOCO            | Horário do intervalo para almoço            | 12:00                                        |
| $HORARIO_ENCERRAMENTO      | Horário do encerramento do evento           | 17:30                                        |

## Programação e Palestrantes

As variáveis usadas para definir Salas, Horários, Palestras, Descrição, Palestrantes e Currículos possuem múltiplos valores, que utilizam o índice numérico N:

| Variável                   | Significado                                   | Exemplo                                             |
| ---------------------------| ----------------------------------------------|-----------------------------------------------------|
| $NOME_SALA_N               | Nome da Sala N                                | Auditório, Sala 408, Laboratório 1, etc             |
| $HORARIO_SLOT_N            | Horário do Slot N                             | 09:30                                               |
| $PALESTRA_N                | Titulo da Palestra N                          | Como organizar eventos do Tchelinux                 | 
| $PALESTRA_N_DESCRICAO      | Descrição da Palestra N                       | Palestra sobre como organizar eventos do Tchelinux  |
| $PALESTRA_N_PALESTRANTE    | Nome do palestrante apresentando a Palestra N | Leonardo Vaz                                        |
| $PALESTRA_N_CURRICULO      | Mini-curriculo do palestrante                 | Leonardo Vaz é voluntário do Tchelinux Porto Alegre |


# Como utilizar este Template?

A seguir são descritos os pré-requisitos e procedimentos necessários para criar o site para um novo evento.

## Pré-Requisitos

- Conhecimentos básicos na Linguagem HTML
- Conhecimentos no uso da ferramenta Git (clone, branch, commit, push etc)
- Possuir uma conta no Github 
- Usar um editor de texto

## Procedimentos

Antes de começar a montar o site do evento, entrar em contato com Leonardo Vaz `<leonardo (ponto) vaz (arroba) gmail (ponto) com>` para solicitar a criação do repositório em `tchelinux-eventos`. Será requisitada o nome da conta no Github para que concedido acesso ao repositório do evento.

 1) Clonar o repositório deste template, substituindo <cidade> pelo nome da Cidade onde o evento ocorrerá:

~~~
   $ git clone https://github.com/tchelinux-eventos/template.git <cidade>
~~~

 2) Alterar o repositório remoto para onde as mudanças serão enviadas, utilizando o endereço será fornecido pelo Leonardo Vaz:

~~~
   $ cd <cidade>
 
   $ git remote rm origin

   $ git remote add origin https://github.com/tchelinux-eventos/<cidade>.git
~~~

 3) Adicionar a URL do evento no arquivo `CNAME`:

~~~
    echo "<cidade>.tchelinux.org" > CNAME 
~~~ 

 4) Realizar as mudanças no arquivo `index.html` do repositório local, adicionando informações do evento.

 5) Alterar também as coordenadas no arquivo `javascript.js`, para que o mapa possa exibir a localização correta da instituição. As coordenadas podem ser obtidas a partir [deste site](http://www.whatsmygps.com/).

~~~
var latitude = -30.0351002;
var longitude = -51.2265906;
~~~

 6) Ao concluir, fazer commit com as mudanças usando o prefixo [2017] (para ajudar na identificação do ano do evento):

~~~
  $ git add .

  $ git commit -m '[2017] Versão inicial do site para o evento do Tchelinux'
~~~

 7) Após realizar todas mudanças (e comitá-las), fazer push para o repositório:

~~~
  $ git push -u origin master
~~~

As mudanças realizas já deverão incidir no site `<cidade>`.tchelinux.org após 15 minutos das modificações (acessar usando um web browser), todavia caso as mudanças não sejam devidamente propagadas, favor entrar em contato com o Leonardo Vaz.
