import { Component, OnInit, ViewChild } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import * as bootstrap from 'bootstrap';
import { RelatorioService } from './relatorio.service';
import { Relatorio } from './relatorio.model';
import { LivroCreateComponent } from './livro-create.component';
import { ReportService } from './report.service';

@Component({
  selector: 'app-root',
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.css']
})
export class AppComponent implements OnInit {

  relatorios: Relatorio[] = [];
  loading: boolean = false;
  errorMessage: string = '';

  @ViewChild(LivroCreateComponent) livroCreateComponent!: LivroCreateComponent;

  title = 'Autores e seus Livros';

  // Dados dos formulários
  autor = { Nome: '' };
  assunto = { Descricao: '' };

  constructor(
    private http: HttpClient, 
    private relatorioService: RelatorioService,
    private reportService: ReportService
  ) { }

  ngOnInit(): void {
    this.fetchRelatorios();
    this.reportService.updateReport$.subscribe(() => {
      this.fetchRelatorios();
    });
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
    const headers = new HttpHeaders({
      'Content-Type': 'application/json',
      'Accept': 'application/json',
    });

    this.http.post('http://127.0.0.1:8000/api/autores', this.autor, { headers })
      .subscribe(response => {
        console.log('Autor cadastrado com sucesso:', response);
        this.fecharModal('modalAutor');
        this.autor.Nome = '';
      });
  }

  cadastrarAssunto() {
    const headers = new HttpHeaders({
      'Content-Type': 'application/json',
      'Accept': 'application/json',
    });

    this.http.post('http://127.0.0.1:8000/api/assuntos', this.assunto, { headers })
      .subscribe(response => {
        console.log('Assunto cadastrado com sucesso:', response);
        this.fecharModal('modalAssunto');
        this.assunto.Descricao = '';
      });
  }

  // Abrir modal de Livro
  openLivroModal() {
    const modal = document.getElementById('modalLivro');
    if (modal) {
      const modalBootstrap = new bootstrap.Modal(modal);
      modalBootstrap.show();
      this.livroCreateComponent.loadAutores(); // Chama o método diretamente
      this.livroCreateComponent.loadAssuntos();
    }
  }

   // Ouve o evento para fechar a modal e atualiza a lista
   onCloseLivroModal() {
    this.fecharModal('modalLivro');
  }

  openModal(tipo: string) {
    const modalId = tipo === 'autor' ? 'modalAutor' : 'modalAssunto';
    const modal = document.getElementById(modalId);
    if (modal) {
      const modalBootstrap = new bootstrap.Modal(modal);
      modalBootstrap.show();
    }
  }

  fecharModal(modalId: string) {
    const modal = document.getElementById(modalId);
    if (modal) {
      const modalBootstrap = bootstrap.Modal.getInstance(modal);
      modalBootstrap?.hide();
      this.fetchRelatorios();
    }
  }
}
