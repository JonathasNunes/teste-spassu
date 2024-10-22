import { Injectable } from '@angular/core';
import { Subject } from 'rxjs';

@Injectable({
  providedIn: 'root',
})
export class ReportService {
  private updateReportSubject = new Subject<void>();
  
  // Observable para o componente principal se inscrever
  updateReport$ = this.updateReportSubject.asObservable();

  // Método para emitir o evento de atualização
  notifyReportUpdate() {
    this.updateReportSubject.next();
  }
}
