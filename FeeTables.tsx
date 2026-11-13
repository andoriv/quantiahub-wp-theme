import React from 'react'
import { feeSchedules, isFree } from '../../data/fees'

/** Both fee schedules (Anexo I) as plain description / tarifa tables. */
export function FeeTables() {
  return (
    <div className="space-y-12">
      {feeSchedules.map((schedule, index) => (
        <div key={schedule.id}>
          <h4 className="font-display text-xl font-bold tracking-[-0.02em] text-ink">
            {index + 1}. Tarifas para {schedule.label}
          </h4>
          <div className="mt-4 overflow-x-auto rounded-2xl border border-ink/10">
            <table className="w-full min-w-[520px] border-collapse text-left text-sm">
              <caption className="sr-only">Tarifas para {schedule.label}</caption>
              <thead>
                <tr className="bg-ink text-white">
                  <th scope="col" className="px-5 py-3 font-semibold">
                    Descripción del cargo / comisión
                  </th>
                  <th scope="col" className="w-[38%] px-5 py-3 font-semibold">
                    Tarifa
                  </th>
                </tr>
              </thead>
              {schedule.groups.map((group) => (
                <tbody key={group.id}>
                  <tr className="bg-mist">
                    <th scope="colgroup" colSpan={2} className="px-5 py-2.5 text-[13px] font-bold text-ink">
                      {group.title}
                    </th>
                  </tr>
                  {group.rows.map((row) => (
                    <tr key={row.concept} className="border-t border-ink/10">
                      <td className="px-5 py-3 align-top text-ink/75">{row.concept}</td>
                      <td className="px-5 py-3 align-top">
                        <span className={`font-semibold ${isFree(row.cost) ? 'text-qh-700' : 'text-ink'}`}>
                          {row.cost}
                        </span>
                        {row.note && <span className="mt-0.5 block text-xs text-ink/55">{row.note}</span>}
                      </td>
                    </tr>
                  ))}
                </tbody>
              ))}
            </table>
          </div>
        </div>
      ))}
    </div>
  )
}
