import React from 'react'
import { Link } from 'react-router-dom'
import { ArrowRightIcon } from 'lucide-react'
import type { LegalBlock } from '../../types/legal'
import { FeeTables } from './FeeTables'

/** Renders the content blocks of one legal section. */
export function LegalBlocks({ blocks }: { blocks: LegalBlock[] }) {
  return (
    <div className="space-y-4">
      {blocks.map((block, index) => {
        switch (block.type) {
          case 'p':
            return (
              <p key={index} className="text-[15px] leading-relaxed text-ink/70">
                {block.text}
              </p>
            )
          case 'list': {
            const List = block.ordered ? 'ol' : 'ul'
            return (
              <List
                key={index}
                className={`space-y-2.5 pl-5 text-[15px] leading-relaxed text-ink/70 ${
                  block.ordered ? 'list-decimal marker:font-semibold marker:text-qh-700' : 'list-disc marker:text-qh'
                }`}
              >
                {block.items.map((item) => (
                  <li key={item} className="pl-1">
                    {item}
                  </li>
                ))}
              </List>
            )
          }
          case 'defs':
            return (
              <dl key={index} className="divide-y divide-ink/10 border-y border-ink/10">
                {block.items.map((item) => (
                  <div key={item.term} className="grid grid-cols-1 gap-1 py-4 md:grid-cols-[220px_1fr] md:gap-6">
                    <dt className="font-display text-[15px] font-bold text-ink">{item.term}</dt>
                    <dd className="text-[15px] leading-relaxed text-ink/70">{item.text}</dd>
                  </div>
                ))}
              </dl>
            )
          case 'contact':
            return (
              <dl key={index} className="grid grid-cols-1 gap-x-8 gap-y-4 rounded-2xl bg-mist p-5 sm:grid-cols-2">
                {block.items.map((item) => (
                  <div key={item.label}>
                    <dt className="text-xs font-semibold text-ink/50">{item.label}</dt>
                    <dd className="mt-1 text-[15px] font-semibold text-ink">
                      {item.href ? (
                        <a
                          href={item.href}
                          target={item.href.startsWith('http') ? '_blank' : undefined}
                          rel={item.href.startsWith('http') ? 'noopener noreferrer' : undefined}
                          className="text-qh-700 underline-offset-4 transition-colors hover:text-ink hover:underline"
                        >
                          {item.value}
                        </a>
                      ) : (
                        item.value
                      )}
                    </dd>
                  </div>
                ))}
              </dl>
            )
          case 'feeTables':
            return <FeeTables key={index} />
          case 'ref':
            return (
              <div key={index} className="flex flex-col items-start gap-4 rounded-2xl bg-mist p-5">
                <p className="text-[15px] leading-relaxed text-ink/70">{block.text}</p>
                <Link
                  to={`/legal/${block.target}`}
                  className="group inline-flex items-center gap-2 text-sm font-semibold text-qh-700 transition-colors hover:text-ink"
                >
                  {block.label}
                  <ArrowRightIcon
                    className="h-4 w-4 transition-transform duration-200 ease-out group-hover:translate-x-1"
                    aria-hidden="true"
                  />
                </Link>
              </div>
            )
          default:
            return null
        }
      })}
    </div>
  )
}
