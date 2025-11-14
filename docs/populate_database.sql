-- ====================================
-- SCRIPT DE POPULAÇÃO DO BANCO DE DADOS
-- FlavorWay - Dados de Exemplo
-- ====================================

USE flavor_way;

-- ====================================
-- TAGS
-- ====================================
INSERT INTO `tags` (`nome`) VALUES
('Tradicional'),
('Prático'),
('Festa'),
('Vegano'),
('Vegetariano'),
('Sem Glúten'),
('Proteína'),
('Carboidrato'),
('Sobremesa'),
('Café da Manhã'),
('Almoço'),
('Jantar'),
('Lanche'),
('Frutos do Mar'),
('Carne Vermelha'),
('Frango'),
('Peixe'),
('Massas'),
('Saudável'),
('Comfort Food');

-- ====================================
-- RECEITAS - NORDESTE
-- ====================================
INSERT INTO `receitas` (`nome`, `descricao`, `imagem`, `ingredientes`, `modo_preparo`, `tempo_preparo`, `pessoas`, `rating`, `dificuldade`, `regiao`, `regiao_id`, `usuario_id`, `destaque`, `badge`, `tempo_cozimento`, `rendimento`, `calorias`, `proteinas`, `carboidratos`, `gorduras`) VALUES
('Acarajé', 'Bolinho de feijão-fradinho frito no dendê, recheado com vatapá, caruru e camarão seco', NULL, '500g de feijão-fradinho\n1 cebola grande\nSal a gosto\nAzeite de dendê para fritar\nCamarão seco para o recheio', '1. Deixe o feijão de molho por 12 horas\n2. Retire as cascas do feijão\n3. Bata no liquidificador com a cebola até formar uma pasta\n4. Tempere com sal\n5. Frite em azeite de dendê quente até dourar\n6. Recheie com vatapá e camarão seco', '40 min', '4-6 porções', 4.8, 'Intermediário', 'Nordeste', 1, 1, 1, 'Tradicional', '30 min', '12 unidades', '280 kcal', '12g', '25g', '15g'),
('Moqueca Baiana', 'Ensopado de peixe com leite de coco, dendê, tomate e pimentões', NULL, '1kg de peixe (badejo ou robalo)\n400ml de leite de coco\n3 tomates\n2 pimentões\n1 cebola\nCoentro\nAzeite de dendê\nSal e pimenta', '1. Tempere o peixe com sal, limão e deixe marinar\n2. Em uma panela, refogue a cebola, tomate e pimentão\n3. Adicione o peixe e o leite de coco\n4. Acrescente o azeite de dendê\n5. Cozinhe por 30-40 minutos em fogo baixo\n6. Finalize com coentro picado', '30 min', '4-5 porções', 4.9, 'Intermediário', 'Nordeste', 1, 1, 1, 'Premium', '40 min', '4 porções', '350 kcal', '28g', '12g', '22g'),
('Baião de Dois', 'Arroz com feijão de corda, queijo coalho, carne seca e temperos nordestinos', NULL, '2 xícaras de arroz\n1 xícara de feijão de corda\n200g de carne seca\n200g de queijo coalho\nCebola, alho\nManteiga de garrafa', '1. Cozinhe o feijão de corda até ficar macio\n2. Dessalgue e cozinhe a carne seca, depois desfie\n3. Refogue alho e cebola na manteiga de garrafa\n4. Adicione o arroz e refogue\n5. Acrescente o feijão cozido e a carne\n6. Finalize com queijo coalho em cubos', '25 min', '4 porções', 4.7, 'Básico', 'Nordeste', 1, 1, 0, NULL, '35 min', '4 porções', '420 kcal', '18g', '52g', '16g'),
('Carne de Sol com Macaxeira', 'Carne de sol desfiada acompanhada de macaxeira cozida e manteiga de garrafa', NULL, '500g de carne de sol\n1kg de macaxeira\nManteiga de garrafa\nCebola', '1. Dessalgue a carne de sol\n2. Cozinhe a carne até ficar macia\n3. Desfie a carne e refogue com cebola\n4. Cozinhe a macaxeira até ficar macia\n5. Sirva a macaxeira com manteiga de garrafa\n6. Acompanhe com a carne desfiada', '20 min', '3-4 porções', 4.6, 'Básico', 'Nordeste', 1, 1, 0, NULL, '2 horas', '4 porções', '380 kcal', '32g', '28g', '18g'),
('Tapioca Recheada', 'Crepe de goma de tapioca com diversos recheios (coco, queijo, carne)', NULL, '200g de goma de tapioca\nRecheios: coco ralado, queijo, carne\nManteiga', '1. Aqueça uma frigideira antiaderente\n2. Espalhe a goma formando um círculo\n3. Aguarde formar uma massa\n4. Adicione o recheio de sua preferência\n5. Dobre ao meio\n6. Sirva quente', '10 min', '2 porções', 4.5, 'Básico', 'Nordeste', 1, 1, 0, 'Rápido', '5 min', '2 unidades', '220 kcal', '8g', '35g', '6g');

-- ====================================
-- RECEITAS - SUDESTE
-- ====================================
INSERT INTO `receitas` (`nome`, `descricao`, `imagem`, `ingredientes`, `modo_preparo`, `tempo_preparo`, `pessoas`, `rating`, `dificuldade`, `regiao`, `regiao_id`, `usuario_id`, `destaque`, `badge`, `tempo_cozimento`, `rendimento`, `calorias`, `proteinas`, `carboidratos`, `gorduras`) VALUES
('Feijoada Completa', 'Prato tradicional com feijão preto, carnes suínas e bovinas, acompanhado de arroz, couve e laranja', NULL, '500g de feijão preto\n300g de costelinha\n200g de linguiça calabresa\n150g de bacon\n100g de paio\n200g de carne seca\nAlho, cebola, louro', '1. Deixe o feijão de molho na véspera\n2. Cozinhe as carnes em panela de pressão\n3. Em outra panela, cozinhe o feijão\n4. Refogue alho, cebola e junte ao feijão\n5. Adicione as carnes ao feijão\n6. Cozinhe por mais 30 minutos\n7. Sirva com arroz, couve, laranja e farofa', '45 min', '8-10 porções', 4.9, 'Intermediário', 'Sudeste', 2, 1, 1, 'Tradicional', '3 horas', '10 porções', '520 kcal', '28g', '45g', '24g'),
('Pão de Queijo', 'Pãozinho de polvilho com queijo, crocante por fora e macio por dentro', NULL, '500g de polvilho azedo\n200g de queijo minas\n1 xícara de leite\n1/2 xícara de óleo\n2 ovos\nSal', '1. Ferva o leite com óleo e sal\n2. Despeje sobre o polvilho e misture\n3. Adicione os ovos um a um\n4. Acrescente o queijo ralado\n5. Modele bolinhas e coloque em forma untada\n6. Asse em forno preaquecido a 180°C por 25 minutos', '15 min', '20 unidades', 4.8, 'Básico', 'Sudeste', 2, 1, 1, 'Popular', '25 min', '20 unidades', '85 kcal', '3g', '10g', '4g'),
('Virado à Paulista', 'Feijão refogado com bacon, linguiça, bisteca, couve e farofa de banana', NULL, '2 xícaras de feijão\n150g de bacon\n2 linguiças\n2 bistecas\n1 maço de couve\n2 bananas\nFarinha de mandioca', '1. Cozinhe o feijão até ficar macio\n2. Frite o bacon e reserve a gordura\n3. Frite a linguiça e a bisteca\n4. Refogue o feijão na gordura do bacon\n5. Prepare a farofa com banana\n6. Refogue a couve\n7. Sirva tudo junto', '30 min', '4 porções', 4.7, 'Intermediário', 'Sudeste', 2, 1, 0, NULL, '40 min', '4 porções', '680 kcal', '35g', '58g', '32g'),
('Tutu de Feijão', 'Feijão cozido e batido com farinha de mandioca, bacon e linguiça', NULL, '2 xícaras de feijão cozido\n3 colheres de farinha de mandioca\n100g de bacon\n1 linguiça\nAlho', '1. Bata metade do feijão no liquidificador\n2. Frite o bacon e a linguiça\n3. Refogue alho na gordura do bacon\n4. Adicione o feijão batido e o feijão inteiro\n5. Acrescente a farinha aos poucos mexendo\n6. Cozinhe até engrossar', '20 min', '6 porções', 4.6, 'Básico', 'Sudeste', 2, 1, 0, NULL, '30 min', '6 porções', '320 kcal', '15g', '38g', '12g'),
('Frango com Quiabo', 'Frango ensopado com quiabo, tomate e temperos mineiros', NULL, '1 frango em pedaços\n300g de quiabo\n3 tomates\nCebola, alho\nCheiro-verde', '1. Tempere o frango e deixe descansar\n2. Doure o frango em uma panela\n3. Adicione cebola e alho\n4. Acrescente os tomates picados\n5. Adicione o quiabo cortado\n6. Cozinhe até o frango ficar macio\n7. Finalize com cheiro-verde', '25 min', '4-5 porções', 4.5, 'Básico', 'Sudeste', 2, 1, 0, NULL, '45 min', '5 porções', '290 kcal', '26g', '18g', '14g');

-- ====================================
-- RECEITAS - SUL
-- ====================================
INSERT INTO `receitas` (`nome`, `descricao`, `imagem`, `ingredientes`, `modo_preparo`, `tempo_preparo`, `pessoas`, `rating`, `dificuldade`, `regiao`, `regiao_id`, `usuario_id`, `destaque`, `badge`, `tempo_cozimento`, `rendimento`, `calorias`, `proteinas`, `carboidratos`, `gorduras`) VALUES
('Churrasco Gaúcho', 'Carnes nobres assadas na grelha ou espeto, temperadas apenas com sal grosso', NULL, 'Picanha\nCostela\nCupim\nSal grosso', '1. Tempere as carnes com sal grosso generosamente\n2. Prepare a churrasqueira com carvão\n3. Aguarde as brasas ficarem uniformes\n4. Asse as carnes virando regularmente\n5. Corte em fatias e sirva quente', '30 min', '10-12 porções', 4.9, 'Intermediário', 'Sul', 3, 1, 1, 'Premium', '1-2 horas', '12 porções', '450 kcal', '38g', '2g', '32g'),
('Barreado', 'Carne bovina cozida lentamente em panela de barro, servida com farinha de mandioca', NULL, '2kg de músculo bovino\n200g de bacon\nFarinha de mandioca\n3 tomates\nCebola, alho\nLouro, cominho', '1. Corte a carne em cubos grandes\n2. Em panela de barro, coloque camadas de carne e bacon\n3. Adicione tomate, cebola, alho e temperos\n4. Vede a panela com farinha e água\n5. Cozinhe em fogo baixo por 12-24 horas\n6. Sirva com farinha de mandioca e banana', '40 min', '6-8 porções', 4.7, 'Avançado', 'Sul', 3, 1, 1, 'Tradicional', '12-24 horas', '8 porções', '480 kcal', '35g', '22g', '28g'),
('Arroz de Carreteiro', 'Arroz refogado com charque desfiado, tomate e temperos', NULL, '2 xícaras de arroz\n300g de charque\n2 tomates\nCebola, alho\nCheiro-verde', '1. Dessalgue e cozinhe o charque\n2. Desfie a carne\n3. Refogue alho e cebola\n4. Adicione o charque e o tomate\n5. Acrescente o arroz e refogue\n6. Adicione água e cozinhe\n7. Finalize com cheiro-verde', '20 min', '4-5 porções', 4.6, 'Básico', 'Sul', 3, 1, 0, NULL, '30 min', '5 porções', '380 kcal', '22g', '48g', '14g'),
('Polenta com Galinha Caipira', 'Polenta cremosa acompanhada de galinha caipira ensopada', NULL, '500g de fubá\n1 galinha caipira\n3 tomates\nCebola, alho\nPimentão', '1. Cozinhe a galinha até ficar macia\n2. Prepare um molho com tomate, cebola e pimentão\n3. Adicione a galinha ao molho\n4. Cozinhe o fubá em água com sal\n5. Mexa até formar uma polenta cremosa\n6. Sirva a polenta com a galinha', '30 min', '6 porções', 4.5, 'Intermediário', 'Sul', 3, 1, 0, NULL, '1 hora', '6 porções', '420 kcal', '28g', '42g', '18g'),
('Cuca Alemã', 'Bolo de origem alemã com farofa doce por cima', NULL, '3 xícaras de farinha de trigo\n1 xícara de açúcar\n150g de manteiga\n2 ovos\n1 xícara de leite\nCanela', '1. Misture farinha, açúcar e fermento\n2. Adicione ovos, leite e metade da manteiga\n3. Despeje em forma untada\n4. Prepare farofa com farinha, açúcar, manteiga e canela\n5. Cubra a massa com a farofa\n6. Asse a 180°C por 40 minutos', '25 min', '12 fatias', 4.8, 'Intermediário', 'Sul', 3, 1, 0, 'Sobremesa', '40 min', '12 fatias', '320 kcal', '6g', '48g', '12g');

-- ====================================
-- RECEITAS - NORTE
-- ====================================
INSERT INTO `receitas` (`nome`, `descricao`, `imagem`, `ingredientes`, `modo_preparo`, `tempo_preparo`, `pessoas`, `rating`, `dificuldade`, `regiao`, `regiao_id`, `usuario_id`, `destaque`, `badge`, `tempo_cozimento`, `rendimento`, `calorias`, `proteinas`, `carboidratos`, `gorduras`) VALUES
('Tacacá', 'Caldo quente de tucupi com goma de tapioca, jambu, camarão seco e pimenta', NULL, '1 litro de tucupi\n200g de goma de tapioca\n1 maço de jambu\n200g de camarão seco\nPimenta de cheiro', '1. Cozinhe o jambu em água fervente\n2. Hidrate o camarão seco\n3. Ferva o tucupi\n4. Prepare a goma em água quente\n5. Monte servindo: goma, jambu, camarão e tucupi quente\n6. Adicione pimenta a gosto', '30 min', '4-6 porções', 4.7, 'Intermediário', 'Norte', 4, 1, 1, 'Tradicional', '20 min', '6 porções', '180 kcal', '12g', '22g', '6g'),
('Pato no Tucupi', 'Pato cozido em molho de tucupi, servido com arroz e jambu', NULL, '1 pato inteiro\n1,5 litros de tucupi\n2 maços de jambu\nAlho\nChicória', '1. Corte o pato em pedaços\n2. Tempere com alho e sal\n3. Doure o pato em uma panela\n4. Adicione o tucupi e cozinhe por 2 horas\n5. Cozinhe o jambu separadamente\n6. Sirva o pato com arroz, jambu e chicória', '40 min', '6-8 porções', 4.8, 'Avançado', 'Norte', 4, 1, 1, 'Premium', '2 horas', '8 porções', '520 kcal', '32g', '28g', '34g'),
('Pirarucu de Casaca', 'Peixe pirarucu em camadas com banana da terra, farinha d\'água e legumes', NULL, '1kg de pirarucu salgado\n4 bananas da terra\nFarinha d\'água\n3 tomates\nPimentão\nLeite de coco', '1. Dessalgue o pirarucu\n2. Cozinhe e desfie o peixe\n3. Frite as bananas em rodelas\n4. Prepare um refogado com tomate e pimentão\n5. Em um refratário, faça camadas alternadas\n6. Finalize com leite de coco e asse', '35 min', '6 porções', 4.6, 'Intermediário', 'Norte', 4, 1, 0, NULL, '45 min', '6 porções', '380 kcal', '28g', '35g', '16g'),
('Açaí na Tigela', 'Polpa de açaí batida servida com granola, banana e mel', NULL, '400g de polpa de açaí congelada\n2 bananas\nGranola\nMel', '1. Bata a polpa de açaí congelada no liquidificador\n2. Adicione um pouco de água se necessário\n3. Despeje em tigelas\n4. Cubra com banana em rodelas\n5. Adicione granola\n6. Regue com mel', '10 min', '2 porções', 4.9, 'Básico', 'Norte', 4, 1, 0, 'Saudável', '5 min', '2 tigelas', '420 kcal', '8g', '68g', '14g'),
('Maniçoba', 'Folhas de mandioca cozidas por dias com carnes defumadas e temperos', NULL, '1kg de folhas de mandioca\n500g de carne de porco\n200g de paio\n150g de bacon\nAlho, cebola', '1. Cozinhe as folhas de mandioca por 7 dias trocando a água\n2. No último dia, adicione as carnes\n3. Tempere com alho e cebola\n4. Cozinhe mais 2 horas\n5. Ajuste o sal\n6. Sirva bem quente', '30 min', '8-10 porções', 4.5, 'Avançado', 'Norte', 4, 1, 0, 'Tradicional', '7 dias', '10 porções', '450 kcal', '25g', '32g', '24g');

-- ====================================
-- RECEITAS - CENTRO-OESTE
-- ====================================
INSERT INTO `receitas` (`nome`, `descricao`, `imagem`, `ingredientes`, `modo_preparo`, `tempo_preparo`, `pessoas`, `rating`, `dificuldade`, `regiao`, `regiao_id`, `usuario_id`, `destaque`, `badge`, `tempo_cozimento`, `rendimento`, `calorias`, `proteinas`, `carboidratos`, `gorduras`) VALUES
('Arroz com Pequi', 'Arroz cozido com fruto do pequi, frango e temperos do cerrado', NULL, '2 xícaras de arroz\n6 pequis\n500g de frango\nAlho, cebola\nCheiro-verde', '1. Cozinhe o pequi em água (cuidado com os espinhos)\n2. Tempere e cozinhe o frango\n3. Refogue alho e cebola\n4. Adicione o arroz e refogue\n5. Acrescente o pequi e o frango\n6. Adicione água e cozinhe\n7. Finalize com cheiro-verde', '25 min', '6 porções', 4.7, 'Intermediário', 'Centro-Oeste', 5, 1, 1, 'Tradicional', '35 min', '6 porções', '380 kcal', '18g', '52g', '14g'),
('Pacu Assado', 'Peixe pacu assado com ervas e limão, típico do pantanal', NULL, '1 pacu grande (2kg)\n2 limões\nAlho, sal\nErvas frescas (alecrim, tomilho)', '1. Limpe e escame o peixe\n2. Tempere com limão, alho e sal\n3. Deixe marinar por 30 minutos\n4. Recheie com ervas frescas\n5. Embrulhe em papel alumínio\n6. Asse em forno a 200°C por 40 minutos', '20 min', '4 porções', 4.6, 'Básico', 'Centro-Oeste', 5, 1, 0, NULL, '40 min', '4 porções', '280 kcal', '32g', '8g', '12g'),
('Empadão Goiano', 'Torta grande recheada com frango, guariroba, pequi e temperos', NULL, '500g de farinha de trigo\n500g de frango\n4 pequis\nGuariroba\n3 ovos\n200g de manteiga', '1. Prepare a massa com farinha, manteiga e ovos\n2. Cozinhe e desfie o frango\n3. Prepare o recheio com frango, pequi e guariroba\n4. Forre uma forma com metade da massa\n5. Adicione o recheio\n6. Cubra com o restante da massa\n7. Asse a 180°C por 1 hora', '40 min', '8-10 porções', 4.8, 'Avançado', 'Centro-Oeste', 5, 1, 1, 'Festa', '1 hora', '10 porções', '420 kcal', '22g', '38g', '22g'),
('Maria Isabel', 'Arroz com charque e temperos, prato típico do Mato Grosso do Sul', NULL, '2 xícaras de arroz\n300g de charque\n2 tomates\nCebola, alho\nCheiro-verde', '1. Dessalgue e cozinhe o charque\n2. Desfie a carne\n3. Refogue alho, cebola e tomate\n4. Adicione o charque\n5. Acrescente o arroz e refogue\n6. Adicione água e cozinhe\n7. Finalize com cheiro-verde', '20 min', '4-5 porções', 4.5, 'Básico', 'Centro-Oeste', 5, 1, 0, NULL, '30 min', '5 porções', '390 kcal', '24g', '46g', '16g'),
('Doce de Leite Caseiro', 'Doce cremoso feito com leite e açúcar, tradicional da região', NULL, '2 litros de leite\n500g de açúcar\n1 colher de bicarbonato de sódio', '1. Em uma panela grande, misture o leite e o açúcar\n2. Adicione o bicarbonato\n3. Leve ao fogo médio mexendo sempre\n4. Cozinhe por cerca de 2 horas\n5. O ponto está certo quando desgrudar do fundo\n6. Deixe esfriar e armazene', '15 min', '15 porções', 4.8, 'Básico', 'Centro-Oeste', 5, 1, 0, 'Sobremesa', '2 horas', '500g', '180 kcal', '4g', '28g', '6g');

-- ====================================
-- INGREDIENTES - NORDESTE
-- ====================================
INSERT INTO `ingredientes` (`receita_id`, `nome`, `categoria`) VALUES
-- Acarajé (1)
(1, 'Feijão-fradinho', 'Grãos'),
(1, 'Camarão seco', 'Outros'),
(1, 'Azeite de dendê', 'Temperos'),
(1, 'Cebola', 'Vegetais'),
(1, 'Sal', 'Temperos'),
-- Moqueca Baiana (2)
(2, 'Peixe (badejo ou robalo)', 'Outros'),
(2, 'Leite de coco', 'Laticínios'),
(2, 'Azeite de dendê', 'Temperos'),
(2, 'Tomate', 'Vegetais'),
(2, 'Pimentão', 'Vegetais'),
(2, 'Cebola', 'Vegetais'),
(2, 'Coentro', 'Temperos'),
-- Baião de Dois (3)
(3, 'Arroz', 'Grãos'),
(3, 'Feijão de corda', 'Grãos'),
(3, 'Queijo coalho', 'Laticínios'),
(3, 'Carne seca', 'Carnes'),
(3, 'Manteiga de garrafa', 'Laticínios'),
(3, 'Cebola', 'Vegetais'),
(3, 'Alho', 'Temperos'),
-- Carne de Sol com Macaxeira (4)
(4, 'Carne de sol', 'Carnes'),
(4, 'Macaxeira', 'Vegetais'),
(4, 'Manteiga de garrafa', 'Laticínios'),
(4, 'Cebola', 'Vegetais'),
-- Tapioca Recheada (5)
(5, 'Goma de tapioca', 'Grãos'),
(5, 'Coco ralado', 'Outros'),
(5, 'Queijo', 'Laticínios'),
(5, 'Manteiga', 'Laticínios');

-- ====================================
-- INGREDIENTES - SUDESTE
-- ====================================
INSERT INTO `ingredientes` (`receita_id`, `nome`, `categoria`) VALUES
-- Feijoada Completa (6)
(6, 'Feijão preto', 'Grãos'),
(6, 'Costelinha de porco', 'Carnes'),
(6, 'Linguiça calabresa', 'Carnes'),
(6, 'Bacon', 'Carnes'),
(6, 'Paio', 'Carnes'),
(6, 'Carne seca', 'Carnes'),
(6, 'Alho', 'Temperos'),
(6, 'Cebola', 'Vegetais'),
(6, 'Louro', 'Temperos'),
-- Pão de Queijo (7)
(7, 'Polvilho azedo', 'Grãos'),
(7, 'Queijo minas', 'Laticínios'),
(7, 'Leite', 'Laticínios'),
(7, 'Óleo', 'Outros'),
(7, 'Ovos', 'Laticínios'),
(7, 'Sal', 'Temperos'),
-- Virado à Paulista (8)
(8, 'Feijão', 'Grãos'),
(8, 'Bacon', 'Carnes'),
(8, 'Linguiça', 'Carnes'),
(8, 'Bisteca', 'Carnes'),
(8, 'Couve', 'Vegetais'),
(8, 'Banana', 'Outros'),
(8, 'Farinha de mandioca', 'Grãos'),
-- Tutu de Feijão (9)
(9, 'Feijão', 'Grãos'),
(9, 'Farinha de mandioca', 'Grãos'),
(9, 'Bacon', 'Carnes'),
(9, 'Linguiça', 'Carnes'),
(9, 'Alho', 'Temperos'),
-- Frango com Quiabo (10)
(10, 'Frango', 'Carnes'),
(10, 'Quiabo', 'Vegetais'),
(10, 'Tomate', 'Vegetais'),
(10, 'Cebola', 'Vegetais'),
(10, 'Alho', 'Temperos');

-- ====================================
-- INGREDIENTES - SUL
-- ====================================
INSERT INTO `ingredientes` (`receita_id`, `nome`, `categoria`) VALUES
-- Churrasco Gaúcho (11)
(11, 'Picanha', 'Carnes'),
(11, 'Costela', 'Carnes'),
(11, 'Cupim', 'Carnes'),
(11, 'Sal grosso', 'Temperos'),
-- Barreado (12)
(12, 'Carne bovina (músculo)', 'Carnes'),
(12, 'Bacon', 'Carnes'),
(12, 'Farinha de mandioca', 'Grãos'),
(12, 'Tomate', 'Vegetais'),
(12, 'Cebola', 'Vegetais'),
(12, 'Alho', 'Temperos'),
(12, 'Louro', 'Temperos'),
(12, 'Cominho', 'Temperos'),
-- Arroz de Carreteiro (13)
(13, 'Arroz', 'Grãos'),
(13, 'Charque', 'Carnes'),
(13, 'Tomate', 'Vegetais'),
(13, 'Cebola', 'Vegetais'),
(13, 'Alho', 'Temperos'),
-- Polenta com Galinha Caipira (14)
(14, 'Fubá', 'Grãos'),
(14, 'Galinha caipira', 'Carnes'),
(14, 'Tomate', 'Vegetais'),
(14, 'Cebola', 'Vegetais'),
(14, 'Pimentão', 'Vegetais'),
-- Cuca Alemã (15)
(15, 'Farinha de trigo', 'Grãos'),
(15, 'Açúcar', 'Outros'),
(15, 'Manteiga', 'Laticínios'),
(15, 'Ovos', 'Laticínios'),
(15, 'Leite', 'Laticínios'),
(15, 'Canela', 'Temperos');

-- ====================================
-- INGREDIENTES - NORTE
-- ====================================
INSERT INTO `ingredientes` (`receita_id`, `nome`, `categoria`) VALUES
-- Tacacá (16)
(16, 'Tucupi', 'Temperos'),
(16, 'Goma de tapioca', 'Grãos'),
(16, 'Jambu', 'Vegetais'),
(16, 'Camarão seco', 'Outros'),
(16, 'Pimenta de cheiro', 'Temperos'),
-- Pato no Tucupi (17)
(17, 'Pato', 'Carnes'),
(17, 'Tucupi', 'Temperos'),
(17, 'Jambu', 'Vegetais'),
(17, 'Alho', 'Temperos'),
(17, 'Chicória', 'Vegetais'),
-- Pirarucu de Casaca (18)
(18, 'Pirarucu', 'Outros'),
(18, 'Banana da terra', 'Outros'),
(18, 'Farinha d\'água', 'Grãos'),
(18, 'Tomate', 'Vegetais'),
(18, 'Pimentão', 'Vegetais'),
(18, 'Leite de coco', 'Laticínios'),
-- Açaí na Tigela (19)
(19, 'Polpa de açaí', 'Outros'),
(19, 'Banana', 'Outros'),
(19, 'Granola', 'Grãos'),
(19, 'Mel', 'Outros'),
-- Maniçoba (20)
(20, 'Folhas de mandioca', 'Vegetais'),
(20, 'Carne de porco', 'Carnes'),
(20, 'Paio', 'Carnes'),
(20, 'Bacon', 'Carnes'),
(20, 'Alho', 'Temperos'),
(20, 'Cebola', 'Vegetais');

-- ====================================
-- INGREDIENTES - CENTRO-OESTE
-- ====================================
INSERT INTO `ingredientes` (`receita_id`, `nome`, `categoria`) VALUES
-- Arroz com Pequi (21)
(21, 'Arroz', 'Grãos'),
(21, 'Pequi', 'Outros'),
(21, 'Frango', 'Carnes'),
(21, 'Alho', 'Temperos'),
(21, 'Cebola', 'Vegetais'),
-- Pacu Assado (22)
(22, 'Pacu', 'Outros'),
(22, 'Limão', 'Outros'),
(22, 'Alho', 'Temperos'),
(22, 'Sal', 'Temperos'),
(22, 'Ervas frescas', 'Temperos'),
-- Empadão Goiano (23)
(23, 'Farinha de trigo', 'Grãos'),
(23, 'Frango', 'Carnes'),
(23, 'Pequi', 'Outros'),
(23, 'Guariroba', 'Vegetais'),
(23, 'Ovos', 'Laticínios'),
(23, 'Manteiga', 'Laticínios'),
-- Maria Isabel (24)
(24, 'Arroz', 'Grãos'),
(24, 'Charque', 'Carnes'),
(24, 'Tomate', 'Vegetais'),
(24, 'Cebola', 'Vegetais'),
(24, 'Alho', 'Temperos'),
-- Doce de Leite Caseiro (25)
(25, 'Leite', 'Laticínios'),
(25, 'Açúcar', 'Outros'),
(25, 'Bicarbonato de sódio', 'Outros');

-- ====================================
-- RELACIONAMENTO RECEITAS-TAGS
-- ====================================
INSERT INTO `receita_tags` (`receita_id`, `tag_id`) VALUES
-- Nordeste
(1, 1), (1, 3), (1, 14),  -- Acarajé: Tradicional, Festa, Frutos do Mar
(2, 1), (2, 17), (2, 14), -- Moqueca: Tradicional, Peixe, Frutos do Mar
(3, 1), (3, 11), (3, 20), -- Baião de Dois: Tradicional, Almoço, Comfort Food
(4, 1), (4, 15), (4, 11), -- Carne de Sol: Tradicional, Carne Vermelha, Almoço
(5, 2), (5, 10), (5, 13), -- Tapioca: Prático, Café da Manhã, Lanche
-- Sudeste
(6, 1), (6, 3), (6, 15),  -- Feijoada: Tradicional, Festa, Carne Vermelha
(7, 1), (7, 2), (7, 13),  -- Pão de Queijo: Tradicional, Prático, Lanche
(8, 1), (8, 11), (8, 20), -- Virado: Tradicional, Almoço, Comfort Food
(9, 1), (9, 11), (9, 8),  -- Tutu: Tradicional, Almoço, Carboidrato
(10, 1), (10, 11), (10, 16), -- Frango com Quiabo: Tradicional, Almoço, Frango
-- Sul
(11, 1), (11, 3), (11, 7), -- Churrasco: Tradicional, Festa, Proteína
(12, 1), (12, 15), (12, 20), -- Barreado: Tradicional, Carne Vermelha, Comfort Food
(13, 1), (13, 11), (13, 15), -- Carreteiro: Tradicional, Almoço, Carne Vermelha
(14, 1), (14, 11), (14, 16), -- Polenta: Tradicional, Almoço, Frango
(15, 1), (15, 9), (15, 13), -- Cuca: Tradicional, Sobremesa, Lanche
-- Norte
(16, 1), (16, 14), (16, 12), -- Tacacá: Tradicional, Frutos do Mar, Jantar
(17, 1), (17, 11), (17, 20), -- Pato no Tucupi: Tradicional, Almoço, Comfort Food
(18, 1), (18, 17), (18, 11), -- Pirarucu: Tradicional, Peixe, Almoço
(19, 2), (19, 19), (19, 10), -- Açaí: Prático, Saudável, Café da Manhã
(20, 1), (20, 3), (20, 15), -- Maniçoba: Tradicional, Festa, Carne Vermelha
-- Centro-Oeste
(21, 1), (21, 11), (21, 16), -- Arroz com Pequi: Tradicional, Almoço, Frango
(22, 1), (22, 17), (22, 19), -- Pacu: Tradicional, Peixe, Saudável
(23, 1), (23, 3), (23, 16), -- Empadão: Tradicional, Festa, Frango
(24, 1), (24, 11), (24, 15), -- Maria Isabel: Tradicional, Almoço, Carne Vermelha
(25, 1), (25, 9), (25, 13); -- Doce de Leite: Tradicional, Sobremesa, Lanche

-- ====================================
-- TÉCNICAS CULINÁRIAS
-- ====================================
INSERT INTO `tecnicas` (`nome`, `descricao`, `dificuldades_tecnica`) VALUES
('Fritura por Imersão', 'Técnica de fritar alimentos completamente submersos em óleo quente', 'Intermediário'),
('Cozimento Lento', 'Cozinhar em baixa temperatura por período prolongado para amaciar carnes', 'Avançado'),
('Refogado', 'Técnica básica de dourar ingredientes em gordura antes de adicionar líquido', 'Básico'),
('Ensopado', 'Cozinhar alimentos em líquido suficiente para cobri-los parcialmente', 'Básico'),
('Assado na Brasa', 'Assar alimentos diretamente sobre brasas ou carvão', 'Intermediário'),
('Defumação', 'Expor alimentos à fumaça para adicionar sabor e conservar', 'Avançado'),
('Branqueamento', 'Mergulhar brevemente em água fervente e depois em água gelada', 'Básico'),
('Redução de Molho', 'Ferver líquidos para evaporar água e concentrar sabores', 'Intermediário'),
('Fermentação', 'Processo de transformação de alimentos através de microorganismos', 'Avançado'),
('Marinada', 'Deixar alimentos em temperos líquidos para amaciar e dar sabor', 'Básico');

-- ====================================
-- TÉCNICAS POR REGIÃO
-- ====================================
INSERT INTO `tecnicas_regiao` (`tecnica_id`, `regiao_id`) VALUES
-- Nordeste
(1, 1), (4, 1), (3, 1), (10, 1),
-- Sudeste
(2, 2), (3, 2), (4, 2), (8, 2),
-- Sul
(5, 3), (2, 3), (6, 3), (10, 3),
-- Norte
(4, 4), (2, 4), (3, 4), (9, 4),
-- Centro-Oeste
(5, 5), (3, 5), (4, 5), (10, 5);

-- ====================================
-- ESTADOS POR REGIÃO
-- ====================================
INSERT INTO `estados_regiao` (`regiao_id`, `nome`, `slug`, `capital`, `descricao`, `ingrediente_destaque`, `especialidades`) VALUES
-- Nordeste
(1, 'Bahia', 'bahia', 'Salvador', 'Berço da culinária afro-brasileira', 'Azeite de dendê', '["Acarajé", "Moqueca", "Vatapá", "Caruru"]'),
(1, 'Pernambuco', 'pernambuco', 'Recife', 'Rica tradição de doces e pratos regionais', 'Tapioca', '["Bolo de rolo", "Carne de sol", "Caldinho de feijão"]'),
(1, 'Ceará', 'ceara', 'Fortaleza', 'Frutos do mar e culinária litorânea', 'Carne de sol', '["Baião de dois", "Tapioca", "Peixada cearense"]'),
(1, 'Maranhão', 'maranhao', 'São Luís', 'Influências indígenas e portuguesas', 'Arroz', '["Arroz de cuxá", "Torta de camarão"]'),

-- Sudeste
(2, 'Minas Gerais', 'minas-gerais', 'Belo Horizonte', 'Cozinha tradicional mineira', 'Queijo minas', '["Pão de queijo", "Tutu", "Frango com quiabo"]'),
(2, 'São Paulo', 'sao-paulo', 'São Paulo', 'Diversidade culinária brasileira', 'Linguiça', '["Virado à paulista", "Feijoada"]'),
(2, 'Rio de Janeiro', 'rio-de-janeiro', 'Rio de Janeiro', 'Culinária carioca e frutos do mar', 'Feijão preto', '["Feijoada", "Bolinho de bacalhau"]'),
(2, 'Espírito Santo', 'espirito-santo', 'Vitória', 'Moquecas e pratos capixabas', 'Peixe', '["Moqueca capixaba", "Torta capixaba"]'),

-- Sul
(3, 'Rio Grande do Sul', 'rio-grande-do-sul', 'Porto Alegre', 'Tradição gaúcha e do churrasco', 'Carne bovina', '["Churrasco", "Carreteiro", "Chimarrão"]'),
(3, 'Santa Catarina', 'santa-catarina', 'Florianópolis', 'Influência europeia e frutos do mar', 'Camarão', '["Marreco recheado", "Sequência de camarão"]'),
(3, 'Paraná', 'parana', 'Curitiba', 'Tradições europeias e indígenas', 'Pinhão', '["Barreado", "Quirera", "Pinhão cozido"]'),

-- Norte
(4, 'Pará', 'para', 'Belém', 'Ingredientes amazônicos únicos', 'Tucupi', '["Tacacá", "Pato no tucupi", "Açaí"]'),
(4, 'Amazonas', 'amazonas', 'Manaus', 'Peixes amazônicos e frutas exóticas', 'Pirarucu', '["Pirarucu", "Tambaqui", "Cupuaçu"]'),
(4, 'Acre', 'acre', 'Rio Branco', 'Culinária indígena e ribeirinha', 'Farinha', '["Pato no tucupi", "Caldeirada"]'),
(4, 'Tocantins', 'tocantins', 'Palmas', 'Peixes de rio e influência goiana', 'Pequi', '["Peixe na telha", "Arroz com pequi"]'),

-- Centro-Oeste
(5, 'Goiás', 'goias', 'Goiânia', 'Ingredientes do cerrado', 'Pequi', '["Arroz com pequi", "Empadão goiano", "Pamonha"]'),
(5, 'Mato Grosso', 'mato-grosso', 'Cuiabá', 'Peixes do pantanal', 'Pacu', '["Mojica de pintado", "Farofa de banana"]'),
(5, 'Mato Grosso do Sul', 'mato-grosso-do-sul', 'Campo Grande', 'Culinária pantaneira', 'Mandioca', '["Maria Isabel", "Chipa", "Sopa paraguaia"]'),
(5, 'Distrito Federal', 'distrito-federal', 'Brasília', 'Fusão de culinárias regionais', 'Mix nacional', '["Receptivo a todas as tradições"]');

-- ====================================
-- CULTURA POR REGIÃO
-- ====================================
INSERT INTO `cultura_regiao` (`regiao_id`, `titulo`, `descricao`, `icon`, `tipo`) VALUES
-- Nordeste
(1, 'Influência Africana', 'O dendê e os temperos marcantes vieram com os africanos escravizados', '🌍', 'influencia'),
(1, 'Festividades Juninas', 'Milho, canjica e outros pratos típicos das festas de São João', '🎊', 'tradicao'),
(1, 'Mercados Populares', 'Feiras livres com produtos regionais frescos', '🏪', 'historia'),

-- Sudeste
(2, 'Tropeirismo', 'Origem de pratos como o feijão tropeiro e tutu de feijão', '🐴', 'historia'),
(2, 'Influência Italiana', 'Massas e pizzas fazem parte do dia a dia paulista', '🍝', 'influencia'),
(2, 'Café Mineiro', 'Tradição do café coado com quitandas', '☕', 'tradicao'),

-- Sul
(3, 'Colonização Alemã', 'Cucas, pães e cervejas artesanais', '🇩🇪', 'influencia'),
(3, 'Cultura Gaúcha', 'Chimarrão e churrasco como identidade cultural', '🧉', 'tradicao'),
(3, 'Rota da Uva', 'Vinhos e espumantes da Serra Gaúcha', '🍷', 'historia'),

-- Norte
(4, 'Povos Indígenas', 'Uso ancestral da mandioca e peixes amazônicos', '🏹', 'influencia'),
(4, 'Ver-o-Peso', 'Mercado histórico com ingredientes da Amazônia', '🏛️', 'historia'),
(4, 'Culinária Ribeirinha', 'Tradições dos povos das margens dos rios', '🚣', 'tradicao'),

-- Centro-Oeste
(5, 'Sabores do Cerrado', 'Pequi, baru e outros frutos nativos', '🌳', 'influencia'),
(5, 'Cultura Pantaneira', 'Peixes de rio e comida de comitiva', '🐊', 'tradicao'),
(5, 'Influência Paraguaia', 'Chipa e sopa paraguaia na fronteira', '🇵🇾', 'influencia');

-- ====================================
-- ALGUNS ESTUDANTES DE EXEMPLO
-- ====================================
INSERT INTO `usuarios` (`username`, `nome`, `email`, `senha`, `ativo`) VALUES
('maria_silva', 'Maria Silva', 'maria.silva@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 1),
('joao_santos', 'João Santos', 'joao.santos@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 1),
('ana_costa', 'Ana Costa', 'ana.costa@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 1);

-- Inserir na tabela estudantes (IDs 4, 5, 6 correspondem aos usuários acima)
INSERT INTO `estudantes` (`usuario_id`, `progresso`) VALUES
(4, 15),
(5, 30),
(6, 45);

-- Vincular usuários ao tipo estudante
INSERT INTO `usuarios_tipo_usuario` (`usuario_id`, `tipo_usuario_id`) VALUES
(4, 2),
(5, 2),
(6, 2);

-- ====================================
-- AVALIAÇÕES DE EXEMPLO
-- ====================================
INSERT INTO `avaliacoes` (`usuario_id`, `receita_id`, `nota`, `comentario`) VALUES
(4, 1, 5, 'Acarajé perfeito! Ficou crocante e muito saboroso.'),
(4, 6, 5, 'Melhor feijoada que já fiz! A família adorou.'),
(5, 2, 4, 'Moqueca deliciosa, mas achei que faltou um pouco mais de dendê.'),
(5, 11, 5, 'Churrasco espetacular! As dicas de preparo ajudaram muito.'),
(6, 7, 5, 'Pão de queijo ficou perfeito, igualzinho ao da vovó!'),
(6, 19, 5, 'Açaí na medida certa, receita prática e saudável.');

-- ====================================
-- FAVORITOS DE EXEMPLO
-- ====================================
INSERT INTO `favoritos` (`estudante_id`, `receita_id`) VALUES
(3, 1),  -- Maria favorita Acarajé
(3, 6),  -- Maria favorita Feijoada
(3, 7),  -- Maria favorita Pão de Queijo
(4, 2),  -- João favorita Moqueca
(4, 11), -- João favorita Churrasco
(5, 7),  -- Ana favorita Pão de Queijo
(5, 19); -- Ana favorita Açaí

-- ====================================
-- MENSAGEM FINAL
-- ====================================
SELECT '✓ Banco de dados populado com sucesso!' AS Mensagem;
SELECT
    (SELECT COUNT(*) FROM receitas) AS 'Total Receitas',
    (SELECT COUNT(*) FROM ingredientes) AS 'Total Ingredientes',
    (SELECT COUNT(*) FROM tags) AS 'Total Tags',
    (SELECT COUNT(*) FROM tecnicas) AS 'Total Técnicas',
    (SELECT COUNT(*) FROM estados_regiao) AS 'Total Estados',
    (SELECT COUNT(*) FROM cultura_regiao) AS 'Total Culturas',
    (SELECT COUNT(*) FROM avaliacoes) AS 'Total Avaliações',
    (SELECT COUNT(*) FROM favoritos) AS 'Total Favoritos';
