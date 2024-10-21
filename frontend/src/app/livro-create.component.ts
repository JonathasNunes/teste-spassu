// livro.component.ts
import { Component, OnInit } from '@angular/core';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { Livro } from './livro.model';
import { AutorService } from './autor.service';
import { Autor } from './autor.model';
import { AssuntoService } from './assunto.service';
import { Assunto } from './assunto.model';

@Component({
  selector: 'app-livro-create',
  templateUrl: './livro-create.component.html'
})
export class LivroCreateComponent implements OnInit {
  livroForm: FormGroup;
  autores: Autor[] = [];
  assuntos: Assunto[] = [];

  constructor(
    private fb: FormBuilder,
    private autorService: AutorService,
    private assuntoService: AssuntoService
  ) {
    this.livroForm = this.fb.group({
      Titulo: ['', Validators.required],
      Editora: ['', Validators.required],
      Edicao: [1, [Validators.required, Validators.min(1)]],
      AnoPublicacao: [new Date().getFullYear(), [
        Validators.required,
        Validators.min(1000),
        Validators.max(new Date().getFullYear()),
        Validators.minLength(4),
        Validators.maxLength(4)
      ]],
      preco: [null, [Validators.required, Validators.min(0)]],
      Autor_CodAu: ['', Validators.required],
      Assunto_codAs: ['', Validators.required]
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
   
    }
  }
}
