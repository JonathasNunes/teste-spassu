export interface Relatorio {
    titulo_livro: string;
    Editora: string;
    autores: string;
    assuntos: string;
    preco: number;
    AnoPublicacao: number;
    Assunto_codAs: string; //Concat de ids de Assunto
    Autor_CodAu: string; //Concat de ids de Autor
}