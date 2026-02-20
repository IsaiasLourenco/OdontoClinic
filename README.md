# 🦷 OdontoClinic - Sistema de Gerenciamento para Clínica Odontológica

> Sistema desenvolvido para gestão completa de clínicas odontológicas, focado em agilidade, segurança e experiência do usuário.

<div align="center">

![PHP](https://img.shields.io/badge/PHP-8.x-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?style=for-the-badge&logo=mysql&logoColor=white)
![PDO](https://img.shields.io/badge/PDO-ORM-8892BF?style=for-the-badge)
![License](https://img.shields.io/badge/license-MIT-green?style=for-the-badge)

</div>

---

## 📋 Sobre o Projeto

O **OdontoClinic** é um sistema web desenvolvido em PHP puro com MySQL para automatizar e organizar a rotina de clínicas odontológicas.

**Objetivo:** Facilitar o controle de pacientes, agendamentos, orçamentos e equipe, proporcionando uma interface intuitiva para recepcionistas e dentistas.

**Status do Desenvolvimento:** 🚧 Em construção (Previsão de MVP: Abril/2024)

---

## ✨ Funcionalidades

### ✅ Implementadas
- [x] **Autenticação Segura**: Login com sessões PHP e validação de credenciais.
- [x] **Fallback Inteligente**: Criação automática de usuário "Administrador" ao iniciar com banco vazio (ambiente de desenvolvimento).
- [x] **Gestão de Cargos Dinâmica**: Vínculo de usuários a cargos por nome (não por ID fixo), garantindo integridade mesmo em bancos resetados.
- [x] **Conexão PDO**: Implementação segura com prepared statements para prevenção de SQL Injection.
- [x] **Suporte a Charset UTF8MB4**: Compatível com acentos, caracteres especiais e emojis.

### 🔄 Em Desenvolvimento (Roadmap)
- [ ] Dashboard administrativo com métricas e resumos.
- [ ] CRUD completo de usuários e cargos.
- [ ] Cadastro e gestão de pacientes (prontuário eletrônico).
- [ ] Agenda de consultas com calendário visual.
- [ ] Orçamentos e controle financeiro básico.
- [ ] Criptografia de senhas com `password_hash()` em produção.

---

## 🛠️ Tecnologias Utilizadas

| Tecnologia | Finalidade |
|------------|------------|
| **PHP 8.x** | Linguagem backend |
| **MySQL 8.0** | Banco de dados relacional |
| **PDO** | Camada de acesso a dados (segurança) |
| **HTML5/CSS3** | Estrutura e estilização frontend |
| **JavaScript** | Interações e validações no cliente |
| **Git/GitHub** | Versionamento e colaboração |

**Ambiente de Desenvolvimento:**
- XAMPP (Apache + MySQL + PHP)
- VS Code (Editor)
- Chromebook com Linux (Crostini) - *Previsão de migração*

---

## 🚀 Instalação e Configuração

### Pré-requisitos
- XAMPP ou servidor LAMP com PHP 8+ e MySQL 8+
- Git instalado

### Passo a Passo

1. **Clone o repositório**
   ```bash
   git clone https://github.com/IsaiasLourenco/OdontoClinic.git
   cd odonto-clinic

2. **Configure o ambiente**
   - Copie a pasta do projeto para C:\xampp\htdocs\ (Windows) ou /var/www/html/ (Linux).
   - Inicie o Apache e MySQL pelo painel do XAMPP.

3. **Crie o banco de dados**
    - Acesse http://localhost/phpmyadmin.
    Execute o script SQL abaixo ou importe o arquivo odontoClinic.sql (quando disponível):
   ```bash
   CREATE DATABASE odontoClinic CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
    USE odontoClinic;

    -- Tabelas cargos e usuarios serão criadas automaticamente pelo sistema
    -- ou via scripts de migração futuros.

4. **Acesse o sistema**
    - Navegue até http://localhost/OdontoClinic/
    - Se o banco estiver vazio, o sistema criará automaticamente:
        - Cargo: Administrador
        - Usuário: usuario@email.com | Senha: 123

## 🗄️ Estrutura do Banco de dados

### Tabela: cargos

| Campo | Tipo | Descrição |
|-------|------|-----------|
| `id` | INT (PK) | Identificador único |
| `nome` | VARCHAR(50) | Nome do cargo (ex: Administrador, Dentista) |

### Tabela: usuarios

| Campo | Tipo | Descrição |
|-------|------|-----------|
| `id` | INT (PK) | Identificador único |
| `nome` | VARCHAR(100) | Nome completo |
| `email` | VARCHAR(100) | E-mail de login (único) |
| `senha` | VARCHAR(255) | Senha (atualmente texto puro para dev) |
| `cargo` | INT (FK) | Vínculo com tabela `cargos` |
| `ativo` | TINYINT(1) | Status do usuário (1=Ativo, 0=Inativo) |
| `data_criacao` | TIMESTAMP | Data de cadastro (automático) |

🔒 Atenção (Ambiente de Produção): Em versão final, as senhas serão criptografadas com password_hash() e validadas com password_verify().

## 📁 Estrutura das pastas

OdontoClinic/<br>
├── css/&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;# Folhas de estilo <br>
├── img/&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;# Imagens e ícones <br>
├── config/<br>
│   └── conexao.php&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;# Conexão PDO com o banco<br>
├── index.php&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;# Tela de login<br>
├── autenticar.php&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;# Lógica de autenticação<br>
├── dashboard.php&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;# Área administrativa (em desenvolvimento)<br>
├── logout.php&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;# Encerramento de sessão<br>
└── README.md&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;# Este arquivo

## 🤝 Contruibuindo

1 - Contribuições são bem-vindas! Para sugerir melhorias:
2 - Faça um Fork do projeto
3 - Crie uma branch para sua feature: git checkout -b minha-feature
4 - Commit suas alterações: git commit -m 'feat: Minha nova feature'
5 - Push para a branch: git push origin minha-feature
6 - Abra um Pull Request

## 📄 Licença

Este projeto está sob a licença MIT. Veja o arquivo LICENSE para mais detalhes.

## 📄 Desenvolvedor

Projeto desenvolvido por Isaias Lourenço da ©Vetor256. <br>
🔗 https://vetor256.com

<div align="center">
<sub>Construído com ❤️ e PHP puro</sub>
</div>