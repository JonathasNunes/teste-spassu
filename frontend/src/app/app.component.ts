import { Component, OnInit } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import * as bootstrap from 'bootstrap';
import { RelatorioService } from './relatorio.service';
import { Relatorio } from './relatorio.model';

@Component({
  selector: 'app-root',
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.css']
})
export class AppComponent implements OnInit {

  relatorios: Relatorio[] = [];
  loading: boolean = false;
  errorMessage: string = '';

  title = 'Autores e seus Livros';

  // Dados dos formulários
  autor = { Nome: '' };
  assunto = { Descricao: '' };

  constructor(private http: HttpClient, private relatorioService: RelatorioService) { }

  ngOnInit(): void {
    this.fetchRelatorios();
  }

  fetchRelatorios(): void {
    this.loading = true;
    this.relatorioService.getRelatorios().subscribe(
      (data: Relatorio[]) => {
        this.relatorios = data;
        this.loading = false;
      },
      (error) => {
        this.errorMessage = 'Ocorreu um erro ao carregar os relatórios.';
        this.loading = false;
      }
    );
  }

  isRelatorioVazio(): boolean {
    return this.relatorios.length === 0;
  }

  // Métodos de cadastro
  cadastrarAutor() {
    console.log('Cadastrando Autor:', this.autor.Nome);
    // // Lógica de cadastro pode ser feita aqui (chamada para API, etc.)
    // this.fecharModal('modalAutor');
    const headers = new HttpHeaders({
      'Content-Type': 'application/json',  // Ajuste para seu backend
      'Accept': 'application/json',
    });

    this.http.post('http://127.0.0.1:8000/api/autores', this.autor, { headers })
      .subscribe(response => {
        console.log('Autor cadastrado com sucesso:', response);
        this.fecharModal('modalAutor');
      });
  }

  cadastrarAssunto() {
    console.log('Cadastrando Assunto:', this.assunto.Descricao);
    // Lógica de cadastro pode ser feita aqui (chamada para API, etc.)
    // this.fecharModal('modalAssunto');
    const headers = new HttpHeaders({
      'Content-Type': 'application/json',  // Ajuste para seu backend
      'Accept': 'application/json',
    });

    this.http.post('http://127.0.0.1:8000/api/assuntos', this.assunto, { headers })
      .subscribe(response => {
        console.log('Assunto cadastrado com sucesso:', response);
        this.fecharModal('modalAssunto');
      });
  }

  // Abrir modal
  openModal(tipo: string) {
    const modalId = tipo === 'autor' ? 'modalAutor' : 'modalAssunto';
    const modal = document.getElementById(modalId);
    if (modal) {
      const modalBootstrap = new bootstrap.Modal(modal);
      modalBootstrap.show();
    }
  }

  // Fechar modal
  fecharModal(modalId: string) {
    const modal = document.getElementById(modalId);
    if (modal) {
      const modalBootstrap = bootstrap.Modal.getInstance(modal);
      modalBootstrap?.hide();
    }
  }
}
