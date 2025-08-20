---
title: 'AwesomeTrades-Copier: A Hybrid Trade Replication System for MetaTrader with Manual Execution Mode'
tags:
  - Algorithmic Trading
  - MetaTrader
  - MQL4
  - MQL5
  - Trade Copier
authors:
  - name: Elijah E. Masanga  
    orcid: 0000-0003-2397-9680 
    corresponding: true  
    affiliation: "1, 2"  
affiliations:
 - name: Damotiva, Mbeya, Tanzania
   index: 1  
 - name: Aifrruis Laboratories, Mbeya, Tanzania  
   index: 2  
date: 23 July 2025  
bibliography: paper.bib  
---  

# Summary

This paper presents AwesomeTrades-Copier, a software solution that supports both Expert Advisor (EA)-based copying and manual trade execution modes, enabling users to replicate trades across MetaTrader 4/5 (MT4/MT5) platforms while maintaining compliance with broker restrictions on automated trading. The system provides:

- **EA Mode**: High-speed trade replication for unrestricted brokers
- **Manual Mode**: Human-like trade execution simulation for restricted environments

Key features include:
- **Broker compliance**: Configurable latency (300-1500ms) in manual mode
- **Cross-platform support**: Compatible with MT4 and MT5
- **Modular architecture**: No external dependencies required

# Statement of Need

## Problem

Proprietary trading firms (e.g., FTMO, The5ers) and brokers frequently implement policies that:

1. Prohibit EAs in evaluation challenges 
2. Restrict trade copiers to prevent latency arbitrage 

Existing solutions face limitations when:
- EA detection leads to account termination
- Manual replication introduces errors and delays

## Solution

AwesomeTrades-Copier addresses these challenges through:

1. **Windows UI automation** for manual mode execution
2. **Direct MQL4/5 integration** for EA mode operation
3. **Adjustable delay profiles** (200-2000ms)

Performance comparison:

| Mode          | Average Latency | Detection Risk |
|---------------|-----------------|----------------|
| EA Mode       | 8ms             | High           |
| Manual Mode   | 650ms           | Low            |

# Technical Implementation

## Architecture
