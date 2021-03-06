# Numenor
Biblioteca PHP de classes utilitárias, para ser usada como um módulo stand-alone em qualquer projeto simples ou em conjunto com frameworks (próprios ou de mercado).

### Motivação
Reconheço que esta biblioteca não representa nada de novo sob o Sol (confesso ser uma pessoa passando por uma severa crise de criatividade), mas esse não é o meu objetivo com este projeto. O principal objetivo é agregar algumas funcionalidades que notei serem necessárias na maior parte dos projetos em que já trabalhei e que frameworks de mercado não oferecem de maneira rápida e intuitiva, ou seja, sem precisar configurar diversos módulos e registrar serviços em views ou controllers ou o que quer que seja.

Trabalhar com um framework é maravilhoso, eu sou o primeiro a reconhecer. No entanto, algumas vezes pode ser frustrante. Ao trabalhar em um projeto com Symphony, por exemplo, lembro de ter tido uma imensa dificuldade para achar como fazer uma alteração em parâmetros enviados da maneira correta. Isso seria sanado se todos os projetos tivessem documentação adequada, mas... _é mais fácil os nossos políticos passem a se preocupar com o bem do País ao invés das suas carreiras políticas_ do que exigir a documentação adequada de projetos desenvolvidos nas condições atuais (com preço baixo e prazo apertado, na sua grande maioria).

Por isso criei esta biblioteca. O objetivo principal é oferecer funcionalidades que os frameworks de mercado consideram triviais demais para implementarem (por exemplo, a funcionalidade do cronômetro de execução para benchmark do tempo de execução de aplicações), mas que o programador pode se sentir desmotivado a implementar como um módulo separado porque, ei, o prazo pra terminar o projeto enorme termina bem antes do que seria o necessário para investir tempo criando essas funcionalidades de maneira reaproveitável. Em alguns casos, o que eu fiz foi criar simples _adaptadores_ para certas classes de frameworks que já existem e funcionam bem, porque reinventar a roda é um pecado mortal na área de desenvolvimento, mas certas interfaces são um pouco confusas ou mal documentadas em sua forma original (sério, a documentação da API do Zend Framwork, por exemplo, deixa bastante a desejar).

Falando em Zend, sempre que possível eu estendi as classes do Framework Zend 2. Fiz isso por duas razões:

* Tenho mais familiaridade com o Zend do que com outros frameworks;
* Os módulos do Zend podem ser facilmente utilizados em modo _standalone_, especialmente quando se usa o Composer (e todo mundo que desenvolve em PHP deveria estar usando o Composer para gerenciar as dependências do seu projeto);

Exatamente por isso considero que esta biblioteca pode ser facilmente integrada com projetos utilizando qualquer framework de mercado, ou mesmo nenhum framework específico. Procuro manter as ações de configuração em um só lugar, de modo que o desenvolvedor possa usar as classes da Numenor sem precisar alterar muito o seu próprio código já existente (de preferência, alterando apenas 1 linha de código para incluir e configurar a biblioteca para uso). Este é o princípio que guia o design dessa biblioteca e dele não pretendo me afastar.

### Disciplina de documentação
Este projeto é também uma forma de me disciplinar melhor na manutenção da documentação de um projeto. Do PHPDoc de cada classe à descrição dos commits feitos ao repositório, e até mesmo a manutenção deste documento, o objetivo é não só manter a documentação do projeto em bom estado (vide reclamação anterior sobre a documentação do Zend Framework), mas também criar a cultura de não deixar a documentação "pra depois" para projetos futuros. Sabe como é: aquela classe que foi criada de última hora e que no afã de codificar acabou ficando sem o bloco de documentação, mas _garanto que no próximo commit ela vai estar lá_... duzentos commits depois, a classe ficou seu o PHPDoc, não é possível gerar a documentação automática correta com o PHPDocumentor, caos e destruição, histeria em massa...

Documentação é importante, minha gente. Por menor que seja o projeto. Certa feita passei 2 semanas refatorando uma biblioteca inteira de classes que haviam sido criados em uma das empresas que trabalhei, especificamente com o objetivo de tornar a minha vida e a dos meus colegas mais fácil. O esforço não foi bem apreciado pela _cabeça pensante_ da empresa (mesmo a maior parte do trabalho tendo sido feita fora de horário de expediente, sem custo - _nóis é burro, sim sinhô, mais nóis tenta fazê as côsa dereito..._), mas o tempo economizado logo depois foi de grande valia. Infelizmente a crise de 2015 começou logo depois e... bem, a história fica triste depois desse ponto.

### Padrões de nomenclatura
Para os namespaces, classes, atributos, métodos, nomes de variáveis e chaves de objetos/arrays utilizados na biblioteca Numenor, os seguintes padrões foram adotados:

- Já que o autor é brasileiro, nomes em português são preferidos, exceto quando não há uma tradução comumente aceita para o termo. Por exemplo, termos como _bootstrap_, _cache_ e _debug_ foram mantidos em inglês, já que as suas respectivas traduções não são muito utilizadas (no caso de específico de _bootstrap_, não achei no dicionário nenhuma tradução que seja adequada para o termo; _tira de sapato_ é a tradução literal engraçadinha que poderia ser sugerida por algum programador lusitano brincalhão).
- Sempre que possível, nomes de métodos incluem um verbo indicando qual ação geral o método está realizando.
- A exceção para a regra acima é para métodos getters e setters, que são prefixados com o termo _get_ e _set_. É o padrão utilizado mesmo em português.
- Procuro sempre escolher o nome que melhor descreva o que um determinado elemento é ou faz, ao invés de optar por abreviações. Douglas Crockford costuma dizer que a maior parte do tempo gasto em programação não é com digitação de código, é olhando para o mesmo tentando entender como funciona e o que deu errado. Quanto mais auto-explicativo um nome utilizado, mais fácil é de entender a aplicação como um todo e menos tempo é gasto na parte mais frustrante e demorada do desenvolvimento.
- Por outro lado, evito ao máximo criar nomes excessivamente declarativos, exceto ao criar classes novas de exceções. Exceções são um caso à parte porque o nome delas deve ajudar a documentar o erro ocorrido. A mensagem de erro nem sempre pode dizer exatamente qual é o erro ocorrido (especialmente nos casos em que a exceção levantada deve ser comunicada ao usuário como uma mensagem de erro amigável).

### PSR-2 (versão 2.1.3+)
A partir da versão 2.1.3, os arquivos da biblioteca foram compatibilizados com as recomendações do padrão PSR-2. Isto não faz nenhuma diferença
na funcionalidade da biblioteca, é claro, mas serve como experiência para lidar com os padrões de código mais aceitos do PHP. E os céus sabem
como o PHP precisa de _algum_ padrão para se ancorar e não virar uma grande bagunça.
