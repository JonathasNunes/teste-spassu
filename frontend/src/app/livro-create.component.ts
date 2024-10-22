// livro.component.ts
import { Component, OnInit, EventEmitter, Output } from '@angular/core';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Livro } from './livro.model';
import { AutorService } from './autor.service';
import { Autor } from './autor.model';
import { AssuntoService } from './assunto.service';
import { Assunto } from './assunto.model';
import { ReportService } from './report.service';

@Component({
  selector: 'app-livro-create',
  templateUrl: './livro-create.component.html'
})
export class LivroCreateComponent implements OnInit {
  @Output() closeModalEvent = new EventEmitter<void>();  // Emite um evento para fechar a modal
  livroForm: FormGroup;
  autores: Autor[] = [];
  assuntos: Assunto[] = [];
  mensagemErro: string = '';

  constructor(
    private http: HttpClient,
    private fb: FormBuilder,
    private autorService: AutorService,
    private assuntoService: AssuntoService,
    private reportService: ReportService
  ) {
    this.livroForm = this.fb.group({
      Titulo: ['', Validators.required],
      Editora: ['', Validators.required],
      Edicao: [1, [Validators.required, Validators.min(1)]],
      AnoPublicacao: [new Date().getFullYear().toString(), [
        Validators.min(1000),
        Validators.max(new Date().getFullYear()),
        Validators.minLength(4),
        Validators.maxLength(4)
      ]],
      preco: [null, [Validators.min(0)]],
      Autor_CodAu: [[], Validators.required],
      Assunto_codAs: [[], Validators.required]
    });
  }

  ngOnInit(): void {
    this.loadAutores();
    this.loadAssuntos();
  }

  loadAutores() {
    this.autorService.getAutores().subscribe(
      (autores: Autor[]) => {
        this.autores = autores;
      },
      (error: any) => {
        console.error('Erro ao carregar autores:', error);
      }
    );
  }

  loadAssuntos() {
    this.assuntoService.getAssuntos().subscribe(
      (assuntos: Assunto[]) => {
        this.assuntos = assuntos;
      },
      (error: any) => {
        console.error('Erro ao carregar assuntos:', error);
      }
    );
  }

  somenteNumero(event: KeyboardEvent): void {
    const key = event.key;
    // Permite apenas números e algumas teclas especiais (Backspace, Delete, Tab, etc.)
    if (!/^[0-9]$/.test(key) && key !== 'Backspace' && key !== 'Tab' && key !== 'ArrowLeft' && key !== 'ArrowRight' && key !== 'Delete') {
      event.preventDefault();
    }
  }

  checkAnoPublicacao() {
    const anoPublicacaoControl = this.livroForm.get('AnoPublicacao');
    if (anoPublicacaoControl) {
      if (anoPublicacaoControl.value.length < 4) {
        anoPublicacaoControl.setErrors({ minlength: true });
      } else {
        anoPublicacaoControl.updateValueAndValidity();
      }
    }
  }

  checkPreco() {
    const precoControl = this.livroForm.get('preco');
    if (precoControl && precoControl.value) {
      // Remove o símbolo de moeda e converte para número
      const precoValue = parseFloat(precoControl.value.replace('R$ ', '').replace('.', '').replace(',', '.'));
      if (isNaN(precoValue) || precoValue <= 0) {
        precoControl.setErrors({ invalid: true });
      }
    }
  }  
  
  onSubmit() {
    if (this.livroForm.valid) {
      const livro: Livro = this.livroForm.value;

      const headers = new HttpHeaders({
        'Content-Type': 'application/json',
        'Accept': 'application/json',
      });
  
      this.http.post('http://127.0.0.1:8000/api/livros', livro, { headers })
        .subscribe({
          next: (response) => {
            console.log('Livro cadastrado com sucesso:', response);
            this.mensagemErro = '';
            this.livroForm.reset({
              Titulo: '', 
              Editora: '',
              Edicao: 1,
              AnoPublicacao: new Date().getFullYear().toString(),
              preco: null,
              Autor_CodAu: [],
              Assunto_codAs: []
            });
            this.reportService.notifyReportUpdate(); // Notificar o componente principal para atualizar o relatório
            this.closeModalEvent.emit();
        },
        error: (errorResponse) => {
          if (errorResponse.status === 422) {
            this.mostrarErrosValidacao(errorResponse.error.errors);
          } else if (errorResponse.status === 404) {
            this.mostrarErroGenerico('Erro 404: Livro não encontrado ou recurso não disponível.');
          } else if (errorResponse.status === 500) {
            this.mostrarErroGenerico('Erro 500: Falha no servidor.');
          } else {// Outros erros
            this.mostrarErroGenerico('Erro desconhecido:' + errorResponse.errorMessage);
          }
        }
      });
    } else {
      this.livroForm.markAllAsTouched();
      return;
    }
  }

  mostrarErrosValidacao(erros: any) {
    const mensagensErro = [];
    for (const campo in erros) {
      if (erros.hasOwnProperty(campo)) {
        mensagensErro.push(...erros[campo]);
      }
    }
    this.mensagemErro = mensagensErro.join(' ');
  }
  
  mostrarErroGenerico(mensagem: string) {
    this.mensagemErro = mensagem;
  }
}
