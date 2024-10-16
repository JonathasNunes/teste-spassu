// book-create.component.ts
import { Component, OnInit } from '@angular/core';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
// import { AuthorService } from './/services/author.service'; // Serviço para buscar autores
// import { SubjectService } from '../services/subject.service'; // Serviço para buscar gêneros
import { Livro } from './livro.model'; // Importa o modelo Book
// import { LivroService } from './services/livro.service'; // 

@Component({
  selector: 'app-livro-create',
  templateUrl: './livro-create.component.html'
})
export class LivroComponent implements OnInit {
  livroForm: FormGroup;
  authors: any[] = []; // Lista de autores
  subjects: any[] = []; // Lista de gêneros

  constructor(
    private fb: FormBuilder,
    // private authorService: AuthorService,
    // private subjectService: SubjectService,
    // private bookService: BookService
  ) {
    this.livroForm = this.fb.group({
      Titulo: ['', Validators.required],
      Editora: ['', Validators.required],
      Edicao: [1, [Validators.required, Validators.min(1)]],
      AnoPublicacao: [new Date().getFullYear(), [Validators.required, Validators.min(1900), Validators.max(new Date().getFullYear())]],
      preco: ['', Validators.required]
    });
  }

  ngOnInit(): void {
    this.loadAutors();
    this.loadAutors();
  }

  loadAutors() {
    // this.authorService.getAuthors().subscribe(data => {
    //   this.authors = data;
    // });
  }

  loadGeneros() {
    // this.subjectService.getSubjects().subscribe(data => {
    //   this.subjects = data;
    // });
  }

  onSubmit() {
    if (this.livroForm.valid) {
      const livro: Livro = this.livroForm.value;
    //   this.livroService.createBook(livro).subscribe(response => {
    //     console.log('Livro cadastrado com sucesso:', response);
    //     // Aqui eu vou redirecionar ou limpar o formulário
    //   }, error => {
    //     console.error('Erro ao cadastrar livro:', error);
    //   });
    }
  }
}
