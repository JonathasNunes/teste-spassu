import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';
import { Relatorio } from './relatorio.model';

@Injectable({
  providedIn: 'root'
})
export class RelatorioService {

  private apiUrl = 'http://127.0.0.1:8000/api/relatorio';

  constructor(private http: HttpClient) { }

  getRelatorios(): Observable<Relatorio[]> {
    return this.http.get<Relatorio[]>(this.apiUrl);
  }
}
