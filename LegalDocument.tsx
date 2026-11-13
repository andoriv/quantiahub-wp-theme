import React, { useState } from 'react'
import { PlusIcon } from 'lucide-react'
import type { LegalDoc } from '../../types/legal'
import { Parallax } from '../motion/Parallax'
import { LegalBlocks } from './LegalBlocks'

/** Documents with many clauses collapse into an accordion so the page stays scannable. */
const ACCORDION_THRESHOLD = 6

type LegalDocumentProps = {
  doc: LegalDoc
  number: number
}

export function LegalDocument({ doc, number }: LegalDocumentProps) {
  const collapsible = doc.sections.length > ACCORDION_THRESHOLD
  const [open, setOpen] = useState<Set<number>>(() => new Set([0]))
  const allOpen = open.size === doc.sections.length

  const toggle = (index: number) =>
    setOpen((prev) => {
      const next = new Set(prev)
      if (next.has(index)) next.delete(index)
      else next.add(index)
      return next
    })

  const toggleAll = () =>
    setOpen(allOpen ? new Set() : new Set(doc.sections.map((_, index) => index)))

  return (
    <section
      id={doc.slug}
      aria-labelledby={`${doc.slug}-title`}
      className="scroll-mt-24 border-t border-ink/10 py-16 first:border-t-0 first:pt-0 md:py-24"
    >
      <Parallax speed={0.12}>
        <p className="font-display text-sm font-bold text-qh-700">
          {String(number).padStart(2, '0')} · Documento
        </p>
        <h2
          id={`${doc.slug}-title`}
          className="mt-3 font-display text-[clamp(1.75rem,1.1rem+2.8vw,3.75rem)] font-extrabold leading-[1] tracking-[-0.04em] text-ink"
        >
          {doc.title}
        </h2>
      </Parallax>
      <p className="mt-5 max-w-xl text-base leading-relaxed text-ink/65">{doc.summary}</p>
      <div className="mt-5 flex flex-wrap items-center gap-x-6 gap-y-2">
        <p className="text-sm text-ink/45">Última actualización: {doc.updated}</p>
        {collapsible && (
          <button
            type="button"
            onClick={toggleAll}
            className="text-sm font-semibold text-qh-700 underline-offset-4 transition-colors hover:text-ink hover:underline"
          >
            {allOpen ? 'Contraer todo' : 'Expandir todo'}
          </button>
        )}
      </div>

      <div className={collapsible ? 'mt-10 border-t border-ink/10' : 'mt-12 space-y-12'}>
        {doc.sections.map((section, index) => {
          if (!collapsible) {
            return (
              <div key={section.heading}>
                <h3 className="mb-4 font-display text-xl font-bold tracking-[-0.02em] text-ink">
                  {section.heading}
                </h3>
                <LegalBlocks blocks={section.blocks} />
              </div>
            )
          }

          const isOpen = open.has(index)
          const panelId = `${doc.slug}-clausula-${index}`
          return (
            <div key={section.heading} className="border-b border-ink/10">
              <h3>
                <button
                  type="button"
                  onClick={() => toggle(index)}
                  aria-expanded={isOpen}
                  aria-controls={panelId}
                  className="group flex w-full items-center justify-between gap-6 py-5 text-left"
                >
                  <span className="font-display text-lg font-bold tracking-[-0.01em] text-ink transition-colors group-hover:text-qh-700">
                    {section.heading}
                  </span>
                  <span
                    className={`flex h-9 w-9 shrink-0 items-center justify-center rounded-full transition-[transform,background-color,color] duration-200 ease-out ${
                      isOpen ? 'rotate-45 bg-qh text-white' : 'bg-mist text-ink'
                    }`}
                  >
                    <PlusIcon className="h-4 w-4" aria-hidden="true" />
                  </span>
                </button>
              </h3>
              {isOpen && (
                <div id={panelId} className="pb-8 pr-2 md:pr-14">
                  <LegalBlocks blocks={section.blocks} />
                </div>
              )}
            </div>
          )
        })}
      </div>
    </section>
  )
}
